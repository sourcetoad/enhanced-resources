<?php

namespace Sourcetoad\EnhancedResources\Concerns;

trait CustomHooks
{
    protected $customHooks = [];

    public function customHook(callable $hook): self
    {
        $this->customHooks[] = $hook;

        return $this;
    }

    protected static function bootCustomHooks()
    {
        static::registerHook(function ($target, array $data) {
            foreach ($target->customHooks ?? [] as $customHook) {
                $data = $customHook($target, $data);
            }

            return $data;
        });

        static::registerMap(function ($resourceCollection) {
            $resourceCollection->collection->each(function ($resource) use ($resourceCollection) {
                foreach ($resourceCollection->customHooks ?? [] as $customHook) {
                    $resource->customHook($customHook);
                }
            });
        });
    }
}