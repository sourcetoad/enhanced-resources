<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests;

use Sourcetoad\EnhancedResources\ResourceCollection;

class ImplicitDefaultCollection extends ResourceCollection
{
    public $collects = ImplicitDefaultResource::class;
}
