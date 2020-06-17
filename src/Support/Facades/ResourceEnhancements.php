<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Sourcetoad\EnhancedResources\Contracts\EnhancementManager;
use Sourcetoad\EnhancedResources\EnhancedResource;

/**
 * @method static callable|null getEnhancement(string $name, string $resource = EnhancedResource::class)
 * @method static bool hasEnhancement(string $name, string $resource = EnhancedResource::class)
 * @method static static register(string $name, callable $enhancement, string $resource = EnhancedResource::class)
 */
class ResourceEnhancements extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EnhancementManager::class;
    }
}
