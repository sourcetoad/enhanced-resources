<?php

namespace Sourcetoad\EnhancedResources\Enhancements;

use Illuminate\Support\Arr;
use Sourcetoad\EnhancedResources\EnhancedResource;

class OnlyEnhancement extends Enhancement
{
    protected $keys;

    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    public function __invoke(EnhancedResource $resource, array $data): array
    {
        return Arr::only($data, $this->keys);
    }
}