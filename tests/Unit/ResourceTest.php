<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Illuminate\Support\Collection;
use Sourcetoad\EnhancedResources\Exceptions\NoDefinedFormatsException;
use Sourcetoad\EnhancedResources\Exceptions\NoFormatSelectedException;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Resource;
use Sourcetoad\EnhancedResources\Tests\ImplicitDefaultResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use stdClass;

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

    /** @dataProvider modificationProvider */
    public function test_resource_can_be_modified_dynamically(Resource $resource, array $expectedData): void
    {
        # Act
        $actualData = $resource->toArray(request());

        # Assert
        $this->assertSame($expectedData, $actualData);
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

    public function modificationProvider(): array
    {
        $john = new stdClass;
        $john->id = 1;
        $john->firstName = 'John';
        $john->lastName = 'Doe';

        return [
            'array modification adding data' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(['middle_initial' => 'A.']),
                'expectedData' => [
                    'first_name' => 'John',
                    'id' => 1,
                    'last_name' => 'Doe',
                    'middle_initial' => 'A.',
                ],
            ],
            'array modification overwriting data' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(['first_name' => 'Jon']),
                'expectedData' => [
                    'first_name' => 'Jon',
                    'id' => 1,
                    'last_name' => 'Doe',
                ],
            ],
            'closure modification adding data' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(fn(array $data) => array_merge($data, ['middle_initial' => 'A.'])),
                'expectedData' => [
                    'first_name' => 'John',
                    'id' => 1,
                    'last_name' => 'Doe',
                    'middle_initial' => 'A.',
                ],
            ],
            'closure modification overwriting data' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(fn(array $data) => array_merge($data, ['first_name' => 'Jon'])),
                'expectedData' => [
                    'first_name' => 'Jon',
                    'id' => 1,
                    'last_name' => 'Doe',
                ],
            ],
            'closure modification completely overwriting data' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(fn() => ['id' => 1]),
                'expectedData' => ['id' => 1],
            ],
            'closure modification accessing resource' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(function (array $data, ImplicitDefaultResource $resource) {
                        $data['id'] = $resource->resource->id * 2;

                        return $data;
                    }),
                'expectedData' => [
                    'first_name' => 'John',
                    'id' => 2,
                    'last_name' => 'Doe',
                ],
            ],
            'invokable modification adding data' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(new class {
                        public function __invoke(array $data): array
                        {
                            return array_merge($data, ['middle_initial' => 'A.']);
                        }
                    }),
                'expectedData' => [
                    'first_name' => 'John',
                    'id' => 1,
                    'last_name' => 'Doe',
                    'middle_initial' => 'A.',
                ],
            ],
            'invokable modification overwriting data' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(new class {
                        public function __invoke(array $data): array
                        {
                            return array_merge($data, ['first_name' => 'Jon']);
                        }
                    }),
                'expectedData' => [
                    'first_name' => 'Jon',
                    'id' => 1,
                    'last_name' => 'Doe',
                ],
            ],
            'invokable modification completely overwriting data' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(new class {
                        public function __invoke(array $data): array
                        {
                            return ['id' => 1];
                        }
                    }),
                'expectedData' => ['id' => 1],
            ],
            'invokable modification accessing resource' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(new class {
                        public function __invoke(array $data, ImplicitDefaultResource $resource): array
                        {
                            $data['id'] = $resource->resource->id * 2;

                            return $data;
                        }
                    }),
                'expectedData' => [
                    'first_name' => 'John',
                    'id' => 2,
                    'last_name' => 'Doe',
                ],
            ],
            'modifications can be chained' => [
                'resource' => ImplicitDefaultResource::make($john)
                    ->modify(['middle_initial' => 'A.'])
                    ->modify(function (array $data): array {
                        $data['first_name'] = 'Jon';

                        return $data;
                    })
                    ->modify(new class {
                        public function __invoke(array $data, ImplicitDefaultResource $resource): array
                        {
                            $data['id'] = $resource->resource->id * 2;

                            return $data;
                        }
                    }),
                'expectedData' => [
                    'first_name' => 'Jon',
                    'id' => 2,
                    'last_name' => 'Doe',
                    'middle_initial' => 'A.',
                ],
            ],
        ];
    }

    # endregion
}
