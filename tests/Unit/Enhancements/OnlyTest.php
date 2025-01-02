<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Enhancements;

use PHPUnit\Framework\Attributes\DataProvider;
use Sourcetoad\EnhancedResources\Enhancements\Only;
use Sourcetoad\EnhancedResources\Enhancements\Traits\HasOnlyEnhancement;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Resource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class OnlyTest extends TestCase
{
    #[DataProvider('resourceProvider')]
    public function test_only_enhancement_can_be_applied_to_resources(
        Resource $resource,
        array $expectedData,
    ): void {
        // Act
        $actualData = $resource->toArray(request());

        // Assert
        $this->assertSame($expectedData, $actualData);
    }

    // region Data Providers

    public static function resourceProvider(): array
    {
        return [
            'applied manually' => [
                'resource' => (new class(null) extends Resource
                {
                    #[Format]
                    public function foo(): array
                    {
                        return [
                            'first_name' => 'John',
                            'id' => 1,
                            'last_name' => 'Doe',
                        ];
                    }
                })->modify(new Only(['id'])),
                'expectedData' => [
                    'id' => 1,
                ],
            ],
            'applied via trait' => [
                'resource' => (new class(null) extends Resource
                {
                    use HasOnlyEnhancement;

                    #[Format]
                    public function foo(): array
                    {
                        return [
                            'first_name' => 'John',
                            'id' => 1,
                            'last_name' => 'Doe',
                        ];
                    }
                })->only('id'),
                'expectedData' => [
                    'id' => 1,
                ],
            ],
        ];
    }

    // endregion
}
