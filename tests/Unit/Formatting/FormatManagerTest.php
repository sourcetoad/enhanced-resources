<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use Sourcetoad\EnhancedResources\Exceptions\FormatNameCollisionException;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Formatting\FormatDefinition;
use Sourcetoad\EnhancedResources\Formatting\FormatManager;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class FormatManagerTest extends TestCase
{
    public function test_formats_are_detected_correctly(): void
    {
        # Arrange
        $subject = new class {
            #[Format('bar')]
            public function barFormat() {}

            #[IsDefault, Format, Format('fooAlias')]
            public function foo() {}
        };

        # Act
        $formats = (new FormatManager($subject))->formats();

        # Assert
        $this->assertContainsOnlyInstancesOf(FormatDefinition::class, $formats);
        $this->assertSame(['bar', 'foo', 'fooAlias'], $formats->keys()->all());
    }

    /** @dataProvider formatNameCollisionProvider */
    public function test_format_name_collisions_are_prevented(object $subject): void
    {
        # Expect
        $this->expectException(FormatNameCollisionException::class);

        # Act
        new FormatManager($subject);
    }

    # region Data Providers

    public function formatNameCollisionProvider(): array
    {
        return [
            'explicit/explicit' => [
                'object' => new class {
                    #[Format('foo')]
                    public function formatOne() {}

                    #[Format('foo')]
                    public function formatTwo() {}
                },
            ],
            'explicit/implicit' => [
                'object' => new class {
                    #[Format]
                    public function foo() {}

                    #[Format('foo')]
                    public function formatTwo() {}
                },
            ],
        ];
    }

    # endregion
}