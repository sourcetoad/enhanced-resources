<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Reflection;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionMethod;
use Sourcetoad\EnhancedResources\Reflection\AttributeMirror;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection\ChildAttribute;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection\ChildAttributesClass;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection\MixedAttributesClass;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection\MixedOrderAttributesClass;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection\NoAttributesClass;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection\ParentAttribute;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Reflection\ParentAttributesClass;
use Sourcetoad\EnhancedResources\Tests\TestCase;

final class AttributeMirrorTest extends TestCase
{
    #[DataProvider('instanceProvider')]
    public function testInstanceReturnsExpectedValue(
        string $attributeClass,
        ReflectionClass|ReflectionMethod $reflection,
        ?string $expectedAttributeClass,
    ): void {
        // Act
        $actual = AttributeMirror::instance($attributeClass, $reflection);

        // Assert
        if ($expectedAttributeClass === null) {
            $this->assertNull($actual);
        } else {
            $this->assertSame($expectedAttributeClass, $actual::class);
        }
    }

    public static function instanceProvider(): array
    {
        $noAttributesClassReflection = new ReflectionClass(NoAttributesClass::class);
        $noAttributesMethodReflection = $noAttributesClassReflection->getMethod('foo');

        $parentAttributesClassReflection = new ReflectionClass(ParentAttributesClass::class);
        $parentAttributesMethodReflection = $parentAttributesClassReflection->getMethod('foo');

        $childAttributesClassReflection = new ReflectionClass(ChildAttributesClass::class);
        $childAttributesMethodReflection = $childAttributesClassReflection->getMethod('foo');

        $mixedAttributesClassReflection = new ReflectionClass(MixedAttributesClass::class);
        $mixedAttributesMethodReflection = $mixedAttributesClassReflection->getMethod('foo');

        return [
            'parent attribute on class with no attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $noAttributesClassReflection,
                'expectedAttributeClass' => null,
            ],
            'parent attribute on class with parent attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $parentAttributesClassReflection,
                'expectedAttributeClass' => ParentAttribute::class,
            ],
            'parent attribute on class with child attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $childAttributesClassReflection,
                'expectedAttributeClass' => ChildAttribute::class,
            ],
            'parent attribute on class with mixed attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $mixedAttributesClassReflection,
                'expectedAttributeClass' => ParentAttribute::class,
            ],
            'child attribute on class with no attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $noAttributesClassReflection,
                'expectedAttributeClass' => null,
            ],
            'child attribute on class with parent attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $parentAttributesClassReflection,
                'expectedAttributeClass' => null,
            ],
            'child attribute on class with child attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $childAttributesClassReflection,
                'expectedAttributeClass' => ChildAttribute::class,
            ],
            'child attribute on class with mixed attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $mixedAttributesClassReflection,
                'expectedAttributeClass' => ChildAttribute::class,
            ],
            'parent attribute on method with no attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $noAttributesMethodReflection,
                'expectedAttributeClass' => null,
            ],
            'parent attribute on method with parent attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $parentAttributesMethodReflection,
                'expectedAttributeClass' => ParentAttribute::class,
            ],
            'parent attribute on method with child attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $childAttributesMethodReflection,
                'expectedAttributeClass' => ChildAttribute::class,
            ],
            'parent attribute on method with mixed attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $mixedAttributesMethodReflection,
                'expectedAttributeClass' => ParentAttribute::class,
            ],
            'child attribute on method with no attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $noAttributesMethodReflection,
                'expectedAttributeClass' => null,
            ],
            'child attribute on method with parent attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $parentAttributesMethodReflection,
                'expectedAttributeClass' => null,
            ],
            'child attribute on method with child attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $childAttributesMethodReflection,
                'expectedAttributeClass' => ChildAttribute::class,
            ],
            'child attribute on method with mixed attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $mixedAttributesMethodReflection,
                'expectedAttributeClass' => ChildAttribute::class,
            ],
        ];
    }

    #[DataProvider('instancesProvider')]
    public function testInstancesReturnsExpectedValue(
        string $attributeClass,
        ReflectionClass|ReflectionMethod $reflection,
        array $expectedResultSet,
    ): void {
        // Act
        $actual = AttributeMirror::instances($attributeClass, $reflection);

        // Assert
        $this->assertContainsOnlyInstancesOf($attributeClass, $actual);
        $this->assertSame(
            $expectedResultSet,
            array_map(fn(object $instance): string => $instance::class, $actual),
        );
    }

    public static function instancesProvider(): array
    {
        $noAttributesClassReflection = new ReflectionClass(NoAttributesClass::class);
        $noAttributesMethodReflection = $noAttributesClassReflection->getMethod('foo');

        $parentAttributesClassReflection = new ReflectionClass(ParentAttributesClass::class);
        $parentAttributesMethodReflection = $parentAttributesClassReflection->getMethod('foo');

        $childAttributesClassReflection = new ReflectionClass(ChildAttributesClass::class);
        $childAttributesMethodReflection = $childAttributesClassReflection->getMethod('foo');

        $mixedAttributesClassReflection = new ReflectionClass(MixedAttributesClass::class);
        $mixedAttributesMethodReflection = $mixedAttributesClassReflection->getMethod('foo');

        $mixedOrderAttributesClassReflection = new ReflectionClass(MixedOrderAttributesClass::class);
        $mixedOrderAttributesMethodReflection = $mixedOrderAttributesClassReflection->getMethod('foo');

        return [
            'parent attribute on class with no attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $noAttributesClassReflection,
                'expectedResultSet' => [],
            ],
            'parent attribute on class with parent attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $parentAttributesClassReflection,
                'expectedResultSet' => [ParentAttribute::class],
            ],
            'parent attribute on class with child attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $childAttributesClassReflection,
                'expectedResultSet' => [ChildAttribute::class, ChildAttribute::class],
            ],
            'parent attribute on class with mixed attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $mixedAttributesClassReflection,
                'expectedResultSet' => [ParentAttribute::class, ChildAttribute::class, ChildAttribute::class],
            ],
            'child attribute on class with no attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $noAttributesClassReflection,
                'expectedResultSet' => [],
            ],
            'child attribute on class with parent attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $parentAttributesClassReflection,
                'expectedResultSet' => [],
            ],
            'child attribute on class with child attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $childAttributesClassReflection,
                'expectedResultSet' => [ChildAttribute::class, ChildAttribute::class],
            ],
            'child attribute on class with mixed attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $mixedAttributesClassReflection,
                'expectedResultSet' => [ChildAttribute::class, ChildAttribute::class],
            ],
            'parent attribute on method with no attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $noAttributesMethodReflection,
                'expectedResultSet' => [],
            ],
            'parent attribute on method with parent attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $parentAttributesMethodReflection,
                'expectedResultSet' => [ParentAttribute::class, ParentAttribute::class],
            ],
            'parent attribute on method with child attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $childAttributesMethodReflection,
                'expectedResultSet' => [ChildAttribute::class, ChildAttribute::class, ChildAttribute::class],
            ],
            'parent attribute on method with mixed attributes' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $mixedAttributesMethodReflection,
                'expectedResultSet' => [ParentAttribute::class, ParentAttribute::class, ChildAttribute::class, ChildAttribute::class],
            ],
            'child attribute on method with no attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $noAttributesMethodReflection,
                'expectedResultSet' => [],
            ],
            'child attribute on method with parent attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $parentAttributesMethodReflection,
                'expectedResultSet' => [],
            ],
            'child attribute on method with child attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $childAttributesMethodReflection,
                'expectedResultSet' => [ChildAttribute::class, ChildAttribute::class, ChildAttribute::class],
            ],
            'child attribute on method with mixed attributes' => [
                'attributeClass' => ChildAttribute::class,
                'reflection' => $mixedAttributesMethodReflection,
                'expectedResultSet' => [ChildAttribute::class, ChildAttribute::class],
            ],
            'mixed order on class is returned in definition order' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $mixedOrderAttributesClassReflection,
                'expectedResultSet' => [ChildAttribute::class, ParentAttribute::class, ChildAttribute::class],
            ],
            'mixed order on method is returned in definition order' => [
                'attributeClass' => ParentAttribute::class,
                'reflection' => $mixedOrderAttributesMethodReflection,
                'expectedResultSet' => [ChildAttribute::class, ParentAttribute::class, ParentAttribute::class, ChildAttribute::class],
            ],
        ];
    }
}
