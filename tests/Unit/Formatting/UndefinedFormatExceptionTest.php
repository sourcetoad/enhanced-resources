<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use Sourcetoad\EnhancedResources\Formatting\UndefinedFormatException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class UndefinedFormatExceptionTest extends TestCase
{
    public function testExposesCorrectData(): void
    {
        // Act
        $exception = new UndefinedFormatException(BasicResource::class, 'missing');

        // Assert
        $this->assertSame(BasicResource::class, $exception->resourceClass);
        $this->assertSame('missing', $exception->format);
        $this->assertEquals([
            'format' => 'missing',
            'resourceClass' => BasicResource::class,
        ], $exception->context());
    }
}
