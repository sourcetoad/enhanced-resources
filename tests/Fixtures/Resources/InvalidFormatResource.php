<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Resources;

use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Resource;

/**
 * @extends Resource<BasicContent>
 * @property-read BasicContent $resource
 */
class InvalidFormatResource extends Resource
{
    #[Format]
    public function base(): string
    {
        return (string) $this->resource->content;
    }
}
