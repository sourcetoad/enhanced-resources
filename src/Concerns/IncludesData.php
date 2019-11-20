<?php

namespace Sourcetoad\EnhancedResources\Concerns;

use Illuminate\Support\Arr;

trait IncludesData
{
    protected $appends = [];

    public function append($attributes)
    {
        $attributes = Arr::wrap($attributes);
        if (!empty($attributes)) {
            array_push($this->appends, ...$attributes);
        }

        $this->appends = array_unique($this->appends);

        return $this;
    }

    protected static function bootIncludesData()
    {
        static::registerHook(function ($resource, array $data) {
            collect($resource->appends)
                ->each(function (string $attribute) use (&$data, $resource) {
                    $data[$attribute] = $resource->resource->getAttribute($attribute);
                });

            return $data;
        });

        static::registerMap(function ($resourceCollection) {
            $resourceCollection->collection->each->append($resourceCollection->appends);
        });
    }
}
