<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Support\Contracts;

use Sourcetoad\EnhancedResources\EnhancedResource;

interface Resourceable
{
    public function toResource(?string $resourceClass = null): EnhancedResource;
}
