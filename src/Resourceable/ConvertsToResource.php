<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Resourceable;

use ReflectionClass;
use Sourcetoad\EnhancedResources\Exceptions\NoResourceDefinedException;
use Sourcetoad\EnhancedResources\Resource;

trait ConvertsToResource
{
    public function toResource(): Resource
    {
        $attributes = (new ReflectionClass($this))->getAttributes(AsResource::class);

        if (empty($attributes)) {
            throw new NoResourceDefinedException($this);
        }

        $resourceClass = $attributes[0]->getArguments()[0];

        return $resourceClass::make($this);
    }
}
