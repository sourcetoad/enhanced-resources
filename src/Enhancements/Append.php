<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Enhancements;

use Sourcetoad\EnhancedResources\EnhancedResource;

class Append
{
    public function __invoke(EnhancedResource $resource, array $data, string ...$keys): array
    {
        foreach ($keys as $key) {
            $data[$key] = $resource->resource[$key];
        }

        return $data;
    }
}
