<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use ReflectionMethod;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;

class FormatDefinition
{
    protected Format $format;
    protected ReflectionMethod $reflection;

    public function __construct(ReflectionMethod $reflection)
    {
        $this->reflection = $reflection;

        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->format = $this->reflection->getAttributes(Format::class)[0]->newInstance();
    }

    public function name(): string
    {
        return $this->format->name() ?? $this->reflection->getName();
    }
}