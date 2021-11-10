<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Enhancements;

use Sourcetoad\EnhancedResources\Enhancements\Except;
use Sourcetoad\EnhancedResources\Enhancements\Traits\HasExceptEnhancement;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Resource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class ExceptTest extends TestCase
{
    /** @dataProvider exceptProvider */
    public function test_except_enhancement_can_be_applied_to_resources(
        Resource $resource,
        array $expectedData,
    ): void {
        # Act
        $actualData = $resource->toArray(request());

        # Assert
        $this->assertSame($expectedData, $actualData);
    }

    # region Data Providers

    public function exceptProvider(): array
    {
        return [
            'applied manually' => [
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
                })->modify(new Except(['id'])),
                'expectedData' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
            ],
            'applied via trait' => [
                'resource' => (new class(null) extends Resource {
                    use HasExceptEnhancement;

                    #[Format]
                    public function foo(): array
                    {
                        return [
                            'first_name' => 'John',
                            'id' => 1,
                            'last_name' => 'Doe',
                        ];
                    }
                })->except('id'),
                'expectedData' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
            ],
        ];
    }

    # endregion
}
