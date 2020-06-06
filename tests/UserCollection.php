<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests;

use Sourcetoad\EnhancedResources\EnhancedCollection;

class UserCollection extends EnhancedCollection
{
    public $collects = UserResource::class;
}
