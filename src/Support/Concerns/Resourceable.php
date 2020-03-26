<?php

namespace Sourcetoad\EnhancedResources\Support\Concerns;

use Sourcetoad\EnhancedResources\EnhancedResource;

trait Resourceable
{
    public function toResource(string $resourceClass): EnhancedResource
    {
        return $resourceClass::make($this);
    }
}