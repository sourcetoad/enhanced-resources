<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
class Format
{
    public function __construct(
        protected ?string $name = null,
    ) {}

    public function name(): ?string
    {
        return $this->name;
    }
}
