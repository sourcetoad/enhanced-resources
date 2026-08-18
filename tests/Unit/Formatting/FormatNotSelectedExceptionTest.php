<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use Sourcetoad\EnhancedResources\Formatting\FormatNotSelectedException;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class FormatNotSelectedExceptionTest extends TestCase
{
    public function testExposesCorrectData(): void
    {
        // Act
        $exception = new FormatNotSelectedException;

        // Assert
        $this->assertSame('No format is selected.', $exception->getMessage());
    }
}
