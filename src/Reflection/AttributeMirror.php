<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Reflection;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionFunctionAbstract;

final class AttributeMirror
{
    /**
     * @template TAttribute of object
     * @template TReflected of object
     *
     * @param  class-string<TAttribute> $attributeClass
     * @param  ReflectionClass<TReflected>|ReflectionFunctionAbstract  $reflection
     * @return TAttribute|null
     */
    public static function instance(
        string $attributeClass,
        ReflectionClass|ReflectionFunctionAbstract $reflection,
    ): ?object {
        return self::instances($attributeClass, $reflection)[0] ?? null;
    }

    /**
     * @template TAttribute of object
     *
     * @param  class-string<TAttribute>  $attributeClass
     * @param  ReflectionClass<object>|ReflectionFunctionAbstract  $reflection
     * @return list<TAttribute>
     */
    public static function instances(
        string $attributeClass,
        ReflectionClass|ReflectionFunctionAbstract $reflection,
    ): array {
        /** @var list<ReflectionAttribute<TAttribute>> $attributeReflections */
        $attributeReflections = $reflection->getAttributes($attributeClass, ReflectionAttribute::IS_INSTANCEOF);

        return array_map(
            fn(ReflectionAttribute $attributeReflection) => $attributeReflection->newInstance(),
            $attributeReflections,
        );
    }
}
