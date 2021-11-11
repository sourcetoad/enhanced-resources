<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Resourceable;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AsResource
{
    public function __construct(
        protected ?string $resourceClass = null,
    ) {}
}
