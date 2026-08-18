<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use ReflectionMethod;
use Sourcetoad\EnhancedResources\Formatting\FormatEntry;
use Sourcetoad\EnhancedResources\Formatting\InvalidFormatResultException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class InvalidFormatResultExceptionTest extends TestCase
{
    public function testExposesCorrectData(): void
    {
        // Arrange
        $formatEntry = new FormatEntry('base', new ReflectionMethod(BasicResource::class, 'base'));

        // Act
        $exception = new InvalidFormatResultException(BasicResource::class, $formatEntry, 'string');

        // Assert
        $this->assertSame(BasicResource::class, $exception->resourceClass);
        $this->assertSame($formatEntry, $exception->formatEntry);
        $this->assertSame('string', $exception->resultType);
        $this->assertEquals([
            'formatMethod' => (string) $formatEntry->methodReflection,
            'formatName' => 'base',
            'resourceClass' => BasicResource::class,
            'resultType' => 'string',
        ], $exception->context());
    }
}
