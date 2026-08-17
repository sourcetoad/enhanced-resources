<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Resources;

use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Resource;

/**
 * @extends Resource<BasicContent>
 * @property-read BasicContent $resource
 */
class NameCollisionResource extends Resource
{
    public const string BASE = 'base';

    #[Format]
    public function base(): array
    {
        return [
            'content' => $this->resource->content,
        ];
    }

    #[Format(self::BASE)]
    public function baseFormat(): array
    {
        return [
            'content' => $this->resource->content,
        ];
    }
}
