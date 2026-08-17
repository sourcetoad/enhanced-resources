<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Illuminate\Container\Attributes\Singleton;
use ReflectionClass;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Reflection\AttributeMirror;

#[Singleton]
class FormatRegistrar
{
    /**
     * @var array<class-string, array<string, FormatEntry>>
     */
    private array $cache = [];

    /**
     * @param class-string $classString
     * @return array<string, FormatEntry>
     */
    public function register(string $classString): array
    {
        return $this->cache[$classString] ??= $this->registerClass($classString);
    }

    /**
     * @param class-string $classString
     */
    public function resolve(string $classString, string $formatName): ?FormatEntry
    {
        if (!isset($this->cache[$classString])) {
            throw new UnregisteredClassException($classString);
        }

        return $this->cache[$classString][$formatName] ?? null;
    }

    /**
     * @param class-string $classString
     * @return array<string, FormatEntry>
     */
    private function registerClass(string $classString): array
    {
        /** @var array<string, list<FormatEntry>> $candidates */
        $candidates = [];

        foreach ((new ReflectionClass($classString))->getMethods() as $methodReflection) {
            $formatAttribute = AttributeMirror::instance(Format::class, $methodReflection);

            if ($formatAttribute === null) {
                continue;
            }

            $formatName = $formatAttribute->name ?? $methodReflection->getName();

            $candidates[$formatName] ??= [];
            $candidates[$formatName][] = new FormatEntry($formatName, $methodReflection);
        }

        if (empty($candidates)) {
            throw new NoDefinedFormatsException($classString);
        }

        $collisions = array_filter(
            $candidates,
            fn(array $formatEntries) => count($formatEntries) > 1,
        );

        if (!empty($collisions)) {
            throw new FormatNameCollisionException($classString, $collisions);
        }

        return array_map(
            fn(array $formatEntries) => $formatEntries[0],
            $candidates,
        );
    }
}
