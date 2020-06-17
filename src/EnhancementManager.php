<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Support\Arr;
use ReflectionClass;
use Sourcetoad\EnhancedResources\Contracts\EnhancementManager as EnhancementManagerContract;

class EnhancementManager implements EnhancementManagerContract
{
    protected array $enhancements = [];

    public function getEnhancement(
        string $name,
        string $resource = EnhancedResource::class
    ): ?callable {
        $enhancement = null;
        $reflection = new ReflectionClass($resource);

        while (!$enhancement && $reflection) {
            $enhancement = Arr::get(
                $this->enhancements,
                $reflection->getName() . '.' . $name
            );

            $reflection = $reflection->getParentClass();
        }

        return $enhancement;
    }

    public function hasEnhancement(
        string $name,
        string $resource = EnhancedResource::class
    ): bool {
        return (bool) $this->getEnhancement($name, $resource);
    }

    /** @return static */
    public function register(string $name, callable $enhancement, string $resource = EnhancedResource::class)
    {
        $this->enhancements[$resource] ??= [];
        $this->enhancements[$resource][$name] = $enhancement;

        return $this;
    }
}
