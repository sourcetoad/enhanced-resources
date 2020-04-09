<?php

namespace Sourcetoad\EnhancedResources;

abstract class Enhancement
{
    abstract public function __invoke(EnhancedResource $resource, array $data): array;
}