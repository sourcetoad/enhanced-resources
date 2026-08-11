<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures;

use Sourcetoad\EnhancedResources\ResourceCollection;
use stdClass;

/**
 * @extends ResourceCollection<stdClass>
 */
final class ConcreteResourceCollection extends ResourceCollection
{
    public $collects = ConcreteResource::class;
}
