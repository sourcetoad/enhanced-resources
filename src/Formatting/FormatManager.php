<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Closure;
use Illuminate\Support\Collection;
use ReflectionMethod;
use ReflectionObject;
use Sourcetoad\EnhancedResources\Exceptions\FormatNameCollisionException;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;

class FormatManager
{
    protected ?FormatDefinition $default;
    protected Collection $formats;
    protected ReflectionObject $reflection;
    protected object $subject;

    public function __construct(object $subject)
    {
        $this->reflection = new ReflectionObject($subject);
        $this->subject = $subject;

        $definitions = (new Collection($this->reflection->getMethods()))
            ->filter(fn(ReflectionMethod $method) => !empty($method->getAttributes(Format::class)))
            ->mapInto(FormatDefinition::class);

        $this->formats = $definitions
            ->tap(Closure::fromCallable([$this, 'preventFormatNameCollisions']))
            ->flatMap(fn(FormatDefinition $definition) => $definition->names()
                ->mapWithKeys(fn(string $name) => [$name => $definition]));

        $this->default = $definitions->filter(fn(FormatDefinition $definition) => $definition->isExplicitlyDefault())
            ->when($definitions->containsOneItem(), fn(Collection $defaults) => $defaults->push($definitions->first()))
            ->first();
    }

    public function default(): FormatDefinition
    {
        return $this->default;
    }

    public function formats(): Collection
    {
        return $this->formats;
    }

    protected function preventFormatNameCollisions(Collection $formatMethods): void
    {
        $formatMethods->flatMap(fn(FormatDefinition $definition) => $definition->names())
            ->countBy()
            ->filter(fn(int $count) => $count > 1)
            ->whenNotEmpty(fn(Collection $collisions) => throw new FormatNameCollisionException(
                $this->subject,
                $collisions->keys()->first(),
            ));
    }
}