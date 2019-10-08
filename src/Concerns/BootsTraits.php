<?php

namespace Sourcetoad\EnhancedResources\Concerns;

trait BootsTraits
{
    protected static $bootedTraits = [];

    protected static function bootTraits(): void
    {
        $traits = class_uses_recursive(static::class);

        foreach ($traits as $trait) {
            if (in_array($trait, static::$bootedTraits) === true) {
                return;
            }

            $method = 'boot'.class_basename($trait);

            if (method_exists(static::class, $method) === true) {
                forward_static_call([static::class, $method]);

                static::$bootedTraits[] = $trait;
            }
        }
    }
}