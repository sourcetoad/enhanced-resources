<?php

namespace Sourcetoad\EnhancedResources\Concerns;

use Illuminate\Support\Str;

trait MasksData
{
    protected $masked = false;

    public function mask()
    {
        $this->masked = true;

        return $this;
    }

    protected static function bootMasksData()
    {
        static::registerHook(function ($resource, array $data) {
            return collect($data)
                ->map(function ($value, $key) use ($resource) {
                    $maskMethod = 'mask'.Str::studly($key);

                    return method_exists($resource, $maskMethod)
                        ? $resource->$maskMethod($value)
                        : $value;
                })
                ->toArray();
        });

        static::registerMap(function ($resourceCollection) {
            if ($resourceCollection->masked === true) {
                $resourceCollection->collection->map->mask();
            }
        });
    }
}