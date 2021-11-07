<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Sourcetoad\EnhancedResources\AnonymousResourceCollection;
use Sourcetoad\EnhancedResources\Tests\ExplicitDefaultResource;
use Sourcetoad\EnhancedResources\Tests\ImplicitDefaultResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use stdClass;

class AnonymousResourceCollectionTest extends TestCase
{
    /** @dataProvider formatProvider */
    public function test_anonymous_collection_records_are_formatted_correctly(
        AnonymousResourceCollection $collection,
        array $expectedData
    ): void {
        # Act
        $actualData = $collection->toArray(request());

        # Assert
        $this->assertSame($expectedData, $actualData);
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
                'resource' => ImplicitDefaultResource::collection([$john, $jane]),
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
                'resource' => ExplicitDefaultResource::collection([$john, $jane]),
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
                'resource' => ExplicitDefaultResource::collection([$john, $jane])->format('bar'),
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
