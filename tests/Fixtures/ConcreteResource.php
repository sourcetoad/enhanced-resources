<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures;

use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Resource;
use stdClass;

/**
 * @extends Resource<stdClass>
 */
final class ConcreteResource extends Resource
{
    public const string BASE = 'base';

    #[Format(self::BASE)]
    public function base(): array
    {
        return [];
    }
}
