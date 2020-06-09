<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

class EnhancedAnonymousCollection extends EnhancedCollection
{
    public $collects;
    
    public function __construct($resource, $collects)
    {
        $this->collects = $collects;

        parent::__construct($resource);
    }
}
