<?php

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Sourcetoad\EnhancedResources\Concerns\CustomHooks;
use Sourcetoad\EnhancedResources\Concerns\Enhanced;
use Sourcetoad\EnhancedResources\Concerns\ExcludesData;
use Sourcetoad\EnhancedResources\Concerns\IncludesData;
use Sourcetoad\EnhancedResources\Concerns\MasksData;

class EnhancedCollection extends ResourceCollection
{
    use CustomHooks, Enhanced, ExcludesData, IncludesData, MasksData;

    public function __construct($resource)
    {
        parent::__construct($resource);

        static::bootTraits();
    }
}
