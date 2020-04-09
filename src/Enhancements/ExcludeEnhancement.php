<?php

namespace Sourcetoad\EnhancedResources\Enhancements;

use Illuminate\Support\Arr;
use Sourcetoad\EnhancedResources\EnhancedResource;

class ExcludeEnhancement extends Enhancement
{
    protected $keysToExclude;

    public function __construct(array $keysToExclude)
    {
        $this->keysToExclude = $keysToExclude;
    }

    public function __invoke(EnhancedResource $resource, array $data): array
    {
        return Arr::except($data, $this->keysToExclude);
    }
}