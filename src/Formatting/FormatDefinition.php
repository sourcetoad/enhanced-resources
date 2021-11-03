<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Illuminate\Support\Collection;
use ReflectionAttribute;
use ReflectionMethod;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;

class FormatDefinition
{
    protected Collection $formats;
    protected ReflectionMethod $reflection;

    public function __construct(ReflectionMethod $reflection)
    {
        $this->reflection = $reflection;

        $this->formats = (new Collection($this->reflection->getAttributes(Format::class)))
            ->map(fn(ReflectionAttribute $attribute) => $attribute->newInstance());
    }

    public function name(): string
    {
        return $this->names()->first();
    }

    public function names(): Collection
    {
        return $this->formats->map(fn(Format $format) => $format->name() ?? $this->reflection->getName())
            ->unique();
    }
}