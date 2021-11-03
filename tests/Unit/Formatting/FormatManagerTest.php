<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Formatting\FormatDefinition;
use Sourcetoad\EnhancedResources\Formatting\FormatManager;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class FormatManagerTest extends TestCase
{
    public function test_formats_are_detected_correctly(): void
    {
        # Arrange
        $subject = new class {
            #[Format('bar')]
            public function barFormat() {}

            #[IsDefault, Format, Format('fooAlias')]
            public function foo() {}
        };

        # Act
        $formats = (new FormatManager($subject))->formats();

        # Assert
        $this->assertContainsOnlyInstancesOf(FormatDefinition::class, $formats);
        $this->assertSame(['bar', 'foo', 'fooAlias'], $formats->keys()->all());
    }
}