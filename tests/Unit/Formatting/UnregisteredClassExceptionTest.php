<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use Sourcetoad\EnhancedResources\Formatting\UnregisteredClassException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class UnregisteredClassExceptionTest extends TestCase
{
    public function testExposesCorrectData(): void
    {
        // Act
        $exception = new UnregisteredClassException(BasicResource::class);

        // Assert
        $this->assertSame(BasicResource::class, $exception->unregisteredClass);
        $this->assertEquals([
            'unregisteredClass' => BasicResource::class,
        ], $exception->context());
    }
}
