<?php

namespace Jasonej\EnhancedResources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait EnhancedResource
{
    protected $appends = [];

    protected $excludes = [];

    protected $only = [];

    public function append(array $keys)
    {
        $this->appends = $keys;

        return $this;
    }

    public function exclude(array $keys)
    {
        $this->excludes = $keys;

        return $this;
    }

    public function only(array $keys)
    {
        $this->only = $keys;

        return $this;
    }

    public function toArray($request)
    {
        if ($this->resource instanceof Model) {
            $this->resource->append($this->appends);
        }

        $data = parent::toArray($request);

        $data = empty($this->excludes) ? $data
            : Arr::except($data, $this->excludes);
        $data = empty($this->only) ? $data
            : Arr::only($data, $this->only);

        return $data;
    }

    public static function collection($resource)
    {
        return tap(new AnonymousResourceCollection($resource, static::class),
            function ($collection) {
                if (property_exists(static::class, 'preserveKeys')) {
                    $collection->preserveKeys = (new static([]))->preserveKeys === true;
                }
            });
    }
}