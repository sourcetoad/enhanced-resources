<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Illuminate\Support\Collection;
use ReflectionAttribute;
use ReflectionMethod;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;

class FormatDefinition
{
    protected Collection $formats;
    protected bool $isExplicitlyDefault;
    protected ReflectionMethod $reflection;

    public function __construct(ReflectionMethod $reflection)
    {
        $this->reflection = $reflection;

        $this->formats = (new Collection($this->reflection->getAttributes(Format::class)))
            ->map(fn(ReflectionAttribute $attribute) => $attribute->newInstance());
        $this->isExplicitlyDefault = !empty($this->reflection->getAttributes(IsDefault::class));
    }

    public function invoke(object $object, $request): mixed
    {
        return $this->reflection->invoke($object, $request);
    }

    public function isExplicitlyDefault(): bool
    {
        return $this->isExplicitlyDefault;
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

    public function reflection(): ReflectionMethod
    {
        return $this->reflection;
    }
}
