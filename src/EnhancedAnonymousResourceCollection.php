<?php

namespace Sourcetoad\EnhancedResources;

class EnhancedAnonymousResourceCollection extends EnhancedCollection
{
    public function __construct($resource, $collects)
    {
        $this->collects = $collects;

        parent::__construct($resource);
    }
}