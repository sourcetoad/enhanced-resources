<?php

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Sourcetoad\EnhancedResources\Concerns\CustomHooks;
use Sourcetoad\EnhancedResources\Concerns\Enhanced;
use Sourcetoad\EnhancedResources\Concerns\ExcludesData;
use Sourcetoad\EnhancedResources\Concerns\IncludesData;
use Sourcetoad\EnhancedResources\Concerns\MasksData;

class EnhancedAnonymousResourceCollection extends AnonymousResourceCollection
{
    use CustomHooks, Enhanced, ExcludesData, IncludesData, MasksData;

    public function __construct($resource, $collects)
    {
        parent::__construct($resource, $collects);

        static::bootTraits();
    }
}