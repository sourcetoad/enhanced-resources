<?php

namespace Sourcetoad\EnhancedResources\Enhancements;

use Sourcetoad\EnhancedResources\EnhancedResource;

abstract class Enhancement
{
    abstract public function __invoke(EnhancedResource $resource, array $data): array;
}