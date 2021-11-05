<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Closure;
use Illuminate\Support\Collection;
use ReflectionMethod;
use ReflectionObject;
use Sourcetoad\EnhancedResources\Exceptions\FormatNameCollisionException;
use Sourcetoad\EnhancedResources\Exceptions\MultipleDefaultFormatsException;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;

class FormatManager
{
    protected ?string $current;
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
            ->tap(Closure::fromCallable([$this, 'preventMultipleDefaultFormats']))
            ->when($definitions->containsOneItem(), fn(Collection $defaults) => $defaults->push($definitions->first()))
            ->first();

        $this->current = $this->default?->name();
    }

    public function current(): ?FormatDefinition
    {
        return $this->formats->get($this->current);
    }

    public function currentName(): ?string
    {
        return $this->current;
    }

    public function default(): FormatDefinition
    {
        return $this->default;
    }

    public function formats(): Collection
    {
        return $this->formats;
    }

    public function hasFormat(string $name): bool
    {
        return $this->formats->has($name);
    }

    public function select(string $name): static
    {
        $this->current = $name;

        return $this;
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

    protected function preventMultipleDefaultFormats(Collection $defaultMethods): void
    {
        if ($defaultMethods->count() > 1) {
            throw new MultipleDefaultFormatsException($this->subject);
        }
    }
}