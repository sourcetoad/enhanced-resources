<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use Closure;
use Sourcetoad\EnhancedResources\Exceptions\FormatNameCollisionException;
use Sourcetoad\EnhancedResources\Exceptions\InvalidFormatException;
use Sourcetoad\EnhancedResources\Exceptions\MultipleDefaultFormatsException;
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

    /** @dataProvider defaultFormatProvider */
    public function test_default_format_is_detected_properly(object $subject, string $expectedFormat): void
    {
        # Act
        $manager = new FormatManager($subject);

        # Assert
        $this->assertSame($expectedFormat, $manager->default()->name());
    }

    public function test_multiple_default_formats_are_prevented(): void
    {
        # Expect
        $this->expectException(MultipleDefaultFormatsException::class);

        # Arrange
        $subject = new class {
            #[IsDefault, Format]
            public function bar() {}

            #[IsDefault, Format]
            public function foo() {}
        };

        # Act
        new FormatManager($subject);
    }

    /** @dataProvider currentFormatProvider */
    public function test_current_format_can_be_set_and_retrieved(Closure $setup, string $expectedFormat): void
    {
        # Act
        /** @var FormatManager $manager */
        $manager = $setup();

        # Assert
        $this->assertSame($expectedFormat, $manager->currentName());
        $this->assertContains($expectedFormat, $manager->current()->names());
    }

    /** @dataProvider formatExistenceProvider */
    public function test_checking_for_a_formats_existence(object $subject, string $formatName, bool $expectedResult): void
    {
        # Arrange
        $manager = new FormatManager($subject);

        # Act
        $actualResult = $manager->hasFormat($formatName);

        # Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /** @dataProvider formatExistenceProvider */
    public function test_checking_for_a_formats_non_existence(object $subject, string $formatName, bool $expectedResult): void
    {
        # Arrange
        $manager = new FormatManager($subject);

        # Act
        $actualResult = $manager->lacksFormat($formatName);

        # Assert
        $this->assertSame(!$expectedResult, $actualResult);
    }

    public function test_selecting_a_non_existent_format_fails(): void
    {
        # Expect
        $this->expectException(InvalidFormatException::class);

        # Arrange
        $manager = new FormatManager(new class {
            #[Format]
            public function foo() {}
        });

        # Act
        $manager->select('bar');
    }

    # region Data Providers

    public function currentFormatProvider(): array
    {
        return [
            'implicit default is used as the initial current format' => [
                fn() => new FormatManager(new class {
                    #[Format]
                    public function foo() {}
                }),
                'foo',
            ],
            'explicit default is used as the initial current format' => [
                fn() => new FormatManager(new class {
                    #[Format]
                    public function bar() {}

                    #[IsDefault, Format]
                    public function foo() {}
                }),
                'foo',
            ],
            'selected by implicit name' => [
                fn() => (new FormatManager(new class {
                    #[Format]
                    public function bar() {}

                    #[IsDefault, Format]
                    public function foo() {}
                }))->select('bar'),
                'bar',
            ],
            'selected by explicit name' => [
                fn() => (new FormatManager(new class {
                    #[Format('foobar')]
                    public function bar() {}

                    #[IsDefault, Format]
                    public function foo() {}
                }))->select('foobar'),
                'foobar',
            ],
            'selected by alias' => [
                fn() => (new FormatManager(new class {
                    #[Format, Format('foobar')]
                    public function bar() {}

                    #[IsDefault, Format]
                    public function foo() {}
                }))->select('foobar'),
                'foobar',
            ],
        ];
    }

    public function defaultFormatProvider(): array
    {
        return [
            'implicit default' => [
                'object' => new class {
                    #[Format]
                    public function foo() {}
                },
                'foo',
            ],
            'explicit default' => [
                'object' => new class {
                    #[Format]
                    public function bar() {}

                    #[IsDefault, Format]
                    public function foo() {}
                },
                'foo',
            ],
        ];
    }

    public function formatExistenceProvider(): array
    {
        return [
            'implicit format exists' => [
                'subject' => new class {
                    #[Format]
                    public function foo() {}
                },
                'formatName' => 'foo',
                'expectedResult' => true,
            ],
            'explicit format exists' => [
                'subject' => new class {
                    #[Format('foobar')]
                    public function foo() {}
                },
                'formatName' => 'foobar',
                'expectedResult' => true,
            ],
            'alias format exists' => [
                'subject' => new class {
                    #[Format, Format('foobar')]
                    public function foo() {}
                },
                'formatName' => 'foobar',
                'expectedResult' => true,
            ],
            'implicit name does not exist if only explicitly named' => [
                'subject' => new class {
                    #[Format('foobar')]
                    public function foo() {}
                },
                'formatName' => 'foo',
                'expectedResult' => false,
            ],
            'non-existent format does not exist' => [
                'subject' => new class {
                    #[Format]
                    public function foo() {}
                },
                'formatName' => 'bar',
                'expectedResult' => false,
            ],
        ];
    }

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