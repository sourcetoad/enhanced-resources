<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Enhancements;

use Illuminate\Support\Arr;
use Sourcetoad\EnhancedResources\EnhancedResource;

class Only
{
    public function __invoke(EnhancedResource $resource, array $data, string ...$keys): array
    {
        return Arr::only($data, $keys);
    }
}
