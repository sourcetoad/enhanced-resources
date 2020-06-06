<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Enhancements;

use Sourcetoad\EnhancedResources\EnhancedResource;

class Call
{
    public function __invoke(EnhancedResource $resource, array $data, callable $callable, ...$params): array
    {
        return $callable($resource, $data, ...$params);
    }
}
