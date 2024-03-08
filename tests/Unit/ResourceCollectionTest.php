<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use PHPUnit\Framework\Attributes\DataProvider;
use Sourcetoad\EnhancedResources\Exceptions\CannotEnhanceBaseResourcesException;
use Sourcetoad\EnhancedResources\ResourceCollection;
use Sourcetoad\EnhancedResources\Tests\ExplicitDefaultResource;
use Sourcetoad\EnhancedResources\Tests\ImplicitDefaultCollection;
use Sourcetoad\EnhancedResources\Tests\ImplicitDefaultResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use stdClass;

class ResourceCollectionTest extends TestCase
{
    #[DataProvider('formatProvider')]
    public function test_collection_records_are_formatted_correctly(
        ResourceCollection $collection,
        array $expectedData
    ): void {
        # Act
        $actualData = $collection->toArray(request());

        # Assert
        $this->assertSame($expectedData, $actualData);
    }

    public function test_exception_is_thrown_if_collecting_non_enhanced_resource(): void
    {
        # Expect
        $this->expectException(CannotEnhanceBaseResourcesException::class);

        # Act
        new class([]) extends ResourceCollection {
            public $collects = JsonResource::class;
        };
    }

    #[DataProvider('modificationProvider')]
    public function test_resource_collection_can_be_modified_dynamically(
        ResourceCollection $resource,
        array $expectedData,
    ): void {
        # Act
        $actualData = $resource->toArray(request());

        # Assert
        $this->assertSame($expectedData, $actualData);
    }

    public function test_response_status_can_be_set(): void
    {
        # Arrange
        $john = new stdClass;
        $john->id = 1;
        $john->firstName = 'John';
        $john->lastName = 'Doe';

        $jane = new stdClass;
        $jane->id = 2;
        $jane->firstName = 'Jane';
        $jane->lastName = 'Doe';

        $collection = new ImplicitDefaultCollection([$john, $jane]);

        # Act
        $response = $collection->setResponseStatus(201)->response();

        # Assert
        $this->assertSame(201, $response->getStatusCode());
    }

    # region Data Providers

    public static function formatProvider(): array
    {
        $john = new stdClass;
        $john->id = 1;
        $john->firstName = 'John';
        $john->lastName = 'Doe';

        $jane = new stdClass;
        $jane->id = 2;
        $jane->firstName = 'Jane';
        $jane->lastName = 'Doe';

        return [
            'implicit default is used' => [
                'collection' => new class([$john, $jane]) extends ResourceCollection {
                    public $collects = ImplicitDefaultResource::class;
                },
                'expectedData' => [
                    [
                        'first_name' => 'John',
                        'id' => 1,
                        'last_name' => 'Doe',
                    ],
                    [
                        'first_name' => 'Jane',
                        'id' => 2,
                        'last_name' => 'Doe',
                    ],
                ],
            ],
            'explicit default is used' => [
                'collection' => new class([$john, $jane]) extends ResourceCollection {
                    public $collects = ExplicitDefaultResource::class;
                },
                'expectedData' => [
                    [
                        'first_name' => 'John',
                        'id' => 1,
                        'last_name' => 'Doe',
                    ],
                    [
                        'first_name' => 'Jane',
                        'id' => 2,
                        'last_name' => 'Doe',
                    ],
                ],
            ],
            'specified format is used' => [
                'collection' => (new class([$john, $jane]) extends ResourceCollection {
                    public $collects = ExplicitDefaultResource::class;
                })->format('bar'),
                'expectedData' => [
                    [
                        'id' => 1,
                        'name' => [
                            'first' => 'John',
                            'last' => 'Doe',
                        ],
                    ],
                    [
                        'id' => 2,
                        'name' => [
                            'first' => 'Jane',
                            'last' => 'Doe',
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function modificationProvider(): array
    {
        $john = new stdClass;
        $john->id = 1;
        $john->firstName = 'John';
        $john->lastName = 'Doe';

        $jane = new stdClass;
        $jane->id = 2;
        $jane->firstName = 'Jane';
        $jane->lastName = 'Doe';

        return [
            'array modification adding data' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
                    ->modify(['middle_initial' => 'A.']),
                'expectedData' => [
                    [
                        'first_name' => 'John',
                        'id' => 1,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                    [
                        'first_name' => 'Jane',
                        'id' => 2,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                ],
            ],
            'array modification overwriting data' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
                    ->modify(['first_name' => 'Jon']),
                'expectedData' => [
                    [
                        'first_name' => 'Jon',
                        'id' => 1,
                        'last_name' => 'Doe',
                    ],
                    [
                        'first_name' => 'Jon',
                        'id' => 2,
                        'last_name' => 'Doe',
                    ],
                ],
            ],
            'closure modification adding data' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
                    ->modify(fn(array $data) => array_merge($data, ['middle_initial' => 'A.'])),
                'expectedData' => [
                    [
                        'first_name' => 'John',
                        'id' => 1,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                    [
                        'first_name' => 'Jane',
                        'id' => 2,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                ],
            ],
            'closure modification overwriting data' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
                    ->modify(fn(array $data) => array_merge($data, ['first_name' => 'Jon'])),
                'expectedData' => [
                    [
                        'first_name' => 'Jon',
                        'id' => 1,
                        'last_name' => 'Doe',
                    ],
                    [
                        'first_name' => 'Jon',
                        'id' => 2,
                        'last_name' => 'Doe',
                    ],
                ],
            ],
            'closure modification completely overwriting data' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
                    ->modify(fn() => ['id' => 1]),
                'expectedData' => [
                    ['id' => 1],
                    ['id' => 1],
                ],
            ],
            'closure modification accessing resource' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
                    ->modify(function (array $data, ImplicitDefaultResource $resource) {
                        $data['id'] = $resource->resource->id * 2;

                        return $data;
                    }),
                'expectedData' => [
                    [
                        'first_name' => 'John',
                        'id' => 2,
                        'last_name' => 'Doe',
                    ],
                    [
                        'first_name' => 'Jane',
                        'id' => 4,
                        'last_name' => 'Doe',
                    ],
                ],
            ],
            'invokable modification adding data' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
                    ->modify(new class {
                        public function __invoke(array $data): array
                        {
                            return array_merge($data, ['middle_initial' => 'A.']);
                        }
                    }),
                'expectedData' => [
                    [
                        'first_name' => 'John',
                        'id' => 1,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                    [
                        'first_name' => 'Jane',
                        'id' => 2,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                ],
            ],
            'invokable modification overwriting data' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
                    ->modify(new class {
                        public function __invoke(array $data): array
                        {
                            return array_merge($data, ['first_name' => 'Jon']);
                        }
                    }),
                'expectedData' => [
                    [
                        'first_name' => 'Jon',
                        'id' => 1,
                        'last_name' => 'Doe',
                    ],
                    [
                        'first_name' => 'Jon',
                        'id' => 2,
                        'last_name' => 'Doe',
                    ],
                ],
            ],
            'invokable modification completely overwriting data' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
                    ->modify(new class {
                        public function __invoke(array $data): array
                        {
                            return ['id' => 1];
                        }
                    }),
                'expectedData' => [
                    ['id' => 1],
                    ['id' => 1],
                ],
            ],
            'invokable modification accessing resource' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
                    ->modify(new class {
                        public function __invoke(array $data, ImplicitDefaultResource $resource): array
                        {
                            $data['id'] = $resource->resource->id * 2;

                            return $data;
                        }
                    }),
                'expectedData' => [
                    [
                        'first_name' => 'John',
                        'id' => 2,
                        'last_name' => 'Doe',
                    ],
                    [
                        'first_name' => 'Jane',
                        'id' => 4,
                        'last_name' => 'Doe',
                    ],
                ],
            ],
            'modifications can be chained' => [
                'resource' => ImplicitDefaultCollection::make([$john, $jane])
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
                    [
                        'first_name' => 'Jon',
                        'id' => 2,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                    [
                        'first_name' => 'Jon',
                        'id' => 4,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                ],
            ],
            'modifications can be applied to basic paginator' => [
                'resource' => ImplicitDefaultCollection::make(new Paginator([$john, $jane], 2))
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
                    [
                        'first_name' => 'Jon',
                        'id' => 2,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                    [
                        'first_name' => 'Jon',
                        'id' => 4,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                ],
            ],
            'modifications can be applied to length aware paginator' => [
                'resource' => ImplicitDefaultCollection::make(new LengthAwarePaginator([$john, $jane], 2, 2))
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
                    [
                        'first_name' => 'Jon',
                        'id' => 2,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                    [
                        'first_name' => 'Jon',
                        'id' => 4,
                        'last_name' => 'Doe',
                        'middle_initial' => 'A.',
                    ],
                ],
            ],
        ];
    }

    # endregion
}
