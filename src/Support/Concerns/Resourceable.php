<?php

namespace Sourcetoad\EnhancedResources\Support\Concerns;

use Sourcetoad\EnhancedResources\EnhancedResource;

trait Resourceable
{
    public function toResource(?string $resourceClass = null): EnhancedResource
    {
        $resourceClass = $resourceClass ?? static::$resourceClass ?? EnhancedResource::class;

        return $resourceClass::make($this);
    }
}
