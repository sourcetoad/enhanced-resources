<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Formatting\FormatDefinition;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class FormatDefinitionTest extends TestCase
{
    #[DataProvider('nameDetectionProvider')]
    public function test_name_is_properly_detected(ReflectionMethod $method, Closure $assertions): void
    {
        // Act
        $definition = new FormatDefinition($method);

        // Assert
        $assertions($definition);
    }

    #[DataProvider('defaultDetectionProvider')]
    public function test_explicit_default_is_properly_detected(ReflectionMethod $method, bool $expected): void
    {
        // Arrange
        $definition = new FormatDefinition($method);

        // Act
        $actual = $definition->isExplicitlyDefault();

        // Assert
        $this->assertSame($expected, $actual);
    }

    // region Data Providers

    public static function defaultDetectionProvider(): array
    {
        $subject = new class
        {
            #[Format('bar')]
            public function barFormat() {}

            #[IsDefault, Format, Format('fooAlias')]
            public function foo() {}
        };

        return [
            'default' => [
                'method' => new ReflectionMethod($subject, 'foo'),
                'expected' => true,
            ],
            'non-default' => [
                'method' => new ReflectionMethod($subject, 'barFormat'),
                'expected' => false,
            ],
        ];
    }

    public static function nameDetectionProvider(): array
    {
        $subject = new class
        {
            #[Format('bar')]
            public function barFormat() {}

            #[Format, Format('fooAlias')]
            public function foo() {}
        };

        return [
            'implicit name' => [
                'method' => new ReflectionMethod($subject, 'foo'),
                'assertions' => function (FormatDefinition $definition) {
                    static::assertSame('foo', $definition->name());
                },
            ],
            'explicit name' => [
                'method' => new ReflectionMethod($subject, 'barFormat'),
                'assertions' => function (FormatDefinition $definition) {
                    static::assertSame('bar', $definition->name());
                },
            ],
            'alias' => [
                'method' => new ReflectionMethod($subject, 'foo'),
                'assertions' => function (FormatDefinition $definition) {
                    static::assertContains('fooAlias', $definition->names());
                },
            ],
        ];
    }

    // endregion
}
