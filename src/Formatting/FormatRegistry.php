<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Illuminate\Container\Attributes\Singleton;
use ReflectionClass;
use ReflectionMethod;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Reflection\AttributeMirror;
use Sourcetoad\EnhancedResources\Resource;

#[Singleton]
class FormatRegistry
{
    /** @var array<class-string<Resource<mixed>>, array<string, FormatEntry>> */
    protected array $cache = [];

    /**
     * @param class-string<Resource<mixed>> $resourceClass
     */
    public function register(string $resourceClass): void
    {
        if (isset($this->cache[$resourceClass])) {
            return;
        }

        $resourceClassReflection = new ReflectionClass($resourceClass);

        /** @var array<string, ReflectionMethod[]> $formatCandidates */
        $formatCandidates = [];

        foreach ($resourceClassReflection->getMethods() as $methodReflection) {
            $formatAttribute = AttributeMirror::instance(Format::class, $methodReflection);

            if ($formatAttribute === null) {
                continue;
            }

            $formatName = $formatAttribute->name ?? $methodReflection->getName();

            $formatCandidates[$formatName] ??= [];
            $formatCandidates[$formatName][] = $methodReflection;
        }

        if (empty($formatCandidates)) {
            throw new NoDefinedFormatsException($resourceClass);
        }

        $nameCollisions = array_filter($formatCandidates, fn(array $methods): bool => count($methods) > 1);

        if (!empty($nameCollisions)) {
            throw new FormatNameCollisionException($resourceClass, $nameCollisions);
        }

        $this->cache[$resourceClass] = collect($formatCandidates)
            ->map(fn(array $methods, string $name) => new FormatEntry($name, $methods[0]))
            ->all();
    }

    /**
     * @param class-string<Resource<mixed>> $resourceClass
     */
    public function resolve(string $resourceClass, string $formatName): ?FormatEntry
    {
        return $this->cache[$resourceClass][$formatName] ?? null;
    }
}
