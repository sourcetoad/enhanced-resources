<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use ReflectionMethod;
use Sourcetoad\EnhancedResources\Formatting\FormatEntry;
use Sourcetoad\EnhancedResources\Formatting\FormatNameCollisionException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\NameCollisionResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class FormatNameCollisionExceptionTest extends TestCase
{
    public function testExposesCorrectData(): void
    {
        // Arrange
        $firstMethod = new ReflectionMethod(NameCollisionResource::class, 'base');
        $secondMethod = new ReflectionMethod(NameCollisionResource::class, 'baseFormat');

        $collisions = [
            'base' => [
                new FormatEntry('base', $firstMethod),
                new FormatEntry('base', $secondMethod),
            ],
        ];

        // Act
        $exception = new FormatNameCollisionException(NameCollisionResource::class, $collisions);

        // Assert
        $this->assertSame(NameCollisionResource::class, $exception->resourceClass);
        $this->assertSame($collisions, $exception->collisions);
        $this->assertEquals([
            'collisions' => [
                'base' => [
                    (string) $firstMethod,
                    (string) $secondMethod,
                ],
            ],
            'resourceClass' => NameCollisionResource::class,
        ], $exception->context());
    }
}
