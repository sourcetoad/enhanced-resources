<?php

namespace Sourcetoad\EnhancedResources\Tests;

use Sourcetoad\EnhancedResources\Enhancements\Enhancement;
use Sourcetoad\EnhancedResources\EnhancedResource;

class ExampleEnhancement extends Enhancement
{
    public function __invoke(EnhancedResource $resource, array $data): array
    {
        // TODO: Implement __invoke() method.
    }
}