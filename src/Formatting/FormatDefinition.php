<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use ReflectionMethod;

class FormatDefinition
{
    protected ReflectionMethod $reflection;

    public function __construct(ReflectionMethod $reflection)
    {
        $this->reflection = $reflection;
    }

    public function name(): string
    {
        return $this->reflection->getName();
    }
}