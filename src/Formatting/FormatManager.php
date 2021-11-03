<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Illuminate\Support\Collection;
use ReflectionMethod;
use ReflectionObject;

class FormatManager
{
    protected Collection $formats;
    protected ReflectionObject $reflection;
    protected object $subject;

    public function __construct(object $subject)
    {
        $this->reflection = new ReflectionObject($subject);
        $this->subject = $subject;
        $this->formats = (new Collection($this->reflection->getMethods()))
            ->filter(fn(ReflectionMethod $method) => !empty($method->getAttributes()))
            ->mapInto(FormatDefinition::class)
            ->flatMap(fn(FormatDefinition $definition) => $definition->names()
                ->mapWithKeys(fn(string $name) => [$name => $definition]));
    }

    public function formats(): Collection
    {
        return $this->formats;
    }
}