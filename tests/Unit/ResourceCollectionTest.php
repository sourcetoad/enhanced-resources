<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Illuminate\Http\Resources\Json\JsonResource;
use Sourcetoad\EnhancedResources\Exceptions\CannotEnhanceBaseResourcesException;
use Sourcetoad\EnhancedResources\ResourceCollection;
use Sourcetoad\EnhancedResources\Tests\ExplicitDefaultResource;
use Sourcetoad\EnhancedResources\Tests\ImplicitDefaultResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use stdClass;

class ResourceCollectionTest extends TestCase
{
    /** @dataProvider formatProvider */
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

    # region Data Providers

    public function formatProvider(): array
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
                'resource' => new class([$john, $jane]) extends ResourceCollection {
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
                'resource' => new class([$john, $jane]) extends ResourceCollection {
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
                'resource' => (new class([$john, $jane]) extends ResourceCollection {
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

    # endregion
}
