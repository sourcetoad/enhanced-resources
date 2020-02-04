<?php

namespace Sourcetoad\EnhancedResources\Concerns;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

trait Enhanced
{
    use BootsTraits;

    protected static $registeredHooks = [];
    protected static $registeredMaps = [];

    public function resolve($request = null)
    {
        if ($this->resource instanceof Collection || $this->resource instanceof Paginator) {
            foreach (static::$registeredMaps as $map) {
                $map($this);
            }

            return $this->collection->map->resolve($request);
        }

        $data = parent::resolve($request);

        foreach (static::$registeredHooks as $hook) {
            $data = $hook($this, $data);
        }

        return $data;
    }

    protected static function registerHook(callable $hook)
    {
        static::$registeredHooks[] = $hook;
    }

    protected static function registerMap(callable $map)
    {
        static::$registeredMaps[] = $map;
    }
}