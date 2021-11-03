<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use Closure;
use ReflectionMethod;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\FormatDefinition;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class FormatDefinitionTest extends TestCase
{
    /** @dataProvider nameDetectionProvider */
    public function test_name_is_properly_detected(ReflectionMethod $method, Closure $assertions): void
    {
        # Act
        $definition = new FormatDefinition($method);

        # Assert
        $assertions($definition);
    }

    # region Data Providers

    public function nameDetectionProvider(): array
    {
        $subject = new class {
            #[Format('bar')]
            public function barFormat() {}

            #[Format]
            public function foo() {}
        };

        return [
            'implicit name' => [
                'method' => new ReflectionMethod($subject, 'foo'),
                'assertions' => function (FormatDefinition $definition) {
                    $this->assertSame('foo', $definition->name());
                },
            ],
            'explicit name' => [
                'method' => new ReflectionMethod($subject, 'barFormat'),
                'assertions' => function (FormatDefinition $definition) {
                    $this->assertSame('bar', $definition->name());
                }
            ],
        ];
    }

    # endregion
}