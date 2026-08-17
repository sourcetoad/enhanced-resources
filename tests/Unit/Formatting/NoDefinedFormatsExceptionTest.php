<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use Sourcetoad\EnhancedResources\Formatting\NoDefinedFormatsException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\ZeroFormatResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class NoDefinedFormatsExceptionTest extends TestCase
{
    public function testExposesCorrectData(): void
    {
        // Act
        $exception = new NoDefinedFormatsException(ZeroFormatResource::class);

        // Assert
        $this->assertSame(ZeroFormatResource::class, $exception->resourceClass);
        $this->assertEquals([
            'resourceClass' => ZeroFormatResource::class,
        ], $exception->context());
    }
}
