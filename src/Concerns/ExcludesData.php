<?php

namespace Sourcetoad\EnhancedResources\Concerns;

use Illuminate\Support\Arr;

trait ExcludesData
{
    protected $excludes = [];

    protected $only = [];

    public function exclude($attributes)
    {
        array_push($this->excludes, ...Arr::wrap($attributes));

        $this->excludes = array_unique($this->excludes);

        return $this;
    }

    public function only($attributes)
    {
        $this->only = Arr::wrap($attributes);

        return $this;
    }

    protected static function bootExcludesData()
    {
        static::registerHook(function ($target, array $data) {
            return Arr::except($data, $target->excludes);
        });

        static::registerHook(function ($resource, array $data) {
            return empty($resource->only) === false
                ? Arr::only($data, $resource->only)
                : $data;
        });

        static::registerMap(function ($resourceCollection) {
            $resourceCollection->collection->each->exclude($resourceCollection->excludes);
        });

        static::registerMap(function ($resourceCollection) {
            $resourceCollection->collection->each->only($resourceCollection->only);
        });
    }
}