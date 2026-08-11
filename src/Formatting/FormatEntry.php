<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use ReflectionMethod;

readonly class FormatEntry
{
    public function __construct(
        public string $name,
        public ReflectionMethod $methodReflection,
    ) {
        //
    }
}
