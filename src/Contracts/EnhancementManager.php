<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Contracts;

use Sourcetoad\EnhancedResources\EnhancedResource;

interface EnhancementManager
{
    public function getEnhancement(
        string $name,
        string $resource = EnhancedResource::class
    ): ?callable;

    public function hasEnhancement(
        string $name,
        string $resource = EnhancedResource::class
    ): bool;

    /** @return static */
    public function register(
        string $name,
        callable $enhancement,
        string $resource = EnhancedResource::class
    );
}
