<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Reflection;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

class AttributeMirror
{
    /**
     * @template TAttribute of object
     * @template TReflectedClass of object
     * @param class-string<TAttribute> $attributeClassString
     * @param ReflectionClass<TReflectedClass>|ReflectionMethod $reflection
     * @return TAttribute|null
     */
    public static function instance(string $attributeClassString, ReflectionClass|ReflectionMethod $reflection): mixed
    {
        return self::instances($attributeClassString, $reflection)[0] ?? null;
    }

    /**
     * @template TAttribute of object
     * @template TReflectedClass of object
     * @param class-string<TAttribute> $attributeClassString
     * @param ReflectionClass<TReflectedClass>|ReflectionMethod $reflection
     * @return list<TAttribute>
     */
    public static function instances(string $attributeClassString, ReflectionClass|ReflectionMethod $reflection): array
    {
        /** @var list<ReflectionAttribute<TAttribute>> $attributeReflections */
        $attributeReflections = $reflection->getAttributes($attributeClassString, ReflectionAttribute::IS_INSTANCEOF);

        return array_map(
            fn(ReflectionAttribute $attributeReflection) => $attributeReflection->newInstance(),
            $attributeReflections,
        );
    }
}
