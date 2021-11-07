<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Sourcetoad\EnhancedResources\Exceptions\NoDefinedFormatsException;
use Sourcetoad\EnhancedResources\Exceptions\NoFormatSelectedException;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Resource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class ResourceTest extends TestCase
{
    /** @dataProvider formatProvider */
    public function test_resource_is_formatted_correctly(Resource $resource, array $expectedData): void
    {
        # Act
        $actualData = $resource->toArray(request());

        # Assert
        $this->assertSame($expectedData, $actualData);
    }

    public function test_exception_is_thrown_if_no_formats_are_defined(): void
    {
        # Expect
        $this->expectException(NoDefinedFormatsException::class);

        # Act
        new class(null) extends Resource {};
    }

    public function test_exception_is_thrown_if_no_format_is_selected(): void
    {
        # Expect
        $this->expectException(NoFormatSelectedException::class);

        # Act
        (new class(null) extends Resource {
            #[Format]
            public function bar() {}

            #[Format]
            public function foo() {}
        })->toArray(request());
    }

    # region Data Providers

    public function formatProvider(): array
    {
        return [
            'implicit default is used' => [
                'resource' => (new class(null) extends Resource {
                    #[Format]
                    public function foo(): array
                    {
                        return [
                            'first_name' => 'John',
                            'id' => 1,
                            'last_name' => 'Doe',
                        ];
                    }
                }),
                'expectedData' => [
                    'first_name' => 'John',
                    'id' => 1,
                    'last_name' => 'Doe',
                ],
            ],
            'explicit default is used' => [
                'resource' => (new class(null) extends Resource {
                    #[Format]
                    public function bar(): array
                    {
                        return [
                            'first_name' => 'John',
                            'id' => 1,
                            'last_name' => 'Doe',
                        ];
                    }

                    #[IsDefault, Format]
                    public function foo(): array
                    {
                        return [
                            'id' => 1,
                            'name' => [
                                'first' => 'John',
                                'last' => 'Doe',
                            ],
                        ];
                    }
                }),
                'expectedData' => [
                    'id' => 1,
                    'name' => [
                        'first' => 'John',
                        'last' => 'Doe',
                    ],
                ],
            ],
            'explicitly selected format is used' => [
                'resource' => (new class(null) extends Resource {
                    #[Format]
                    public function bar(): array
                    {
                        return [
                            'first_name' => 'John',
                            'id' => 1,
                            'last_name' => 'Doe',
                        ];
                    }

                    #[IsDefault, Format]
                    public function foo(): array
                    {
                        return [
                            'id' => 1,
                            'name' => [
                                'first' => 'John',
                                'last' => 'Doe',
                            ],
                        ];
                    }
                })->format('bar'),
                'expectedData' => [
                    'first_name' => 'John',
                    'id' => 1,
                    'last_name' => 'Doe',
                ],
            ],
        ];
    }

    # endregion
}
