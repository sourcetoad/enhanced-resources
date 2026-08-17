<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Sourcetoad\EnhancedResources\Formatting\FormatEntry;
use Sourcetoad\EnhancedResources\Formatting\FormatHandler;
use Sourcetoad\EnhancedResources\Formatting\FormatRegistrar;
use Sourcetoad\EnhancedResources\Formatting\NoDefinedFormatsException;
use Sourcetoad\EnhancedResources\Formatting\UndefinedFormatException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\MultiFormatResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\ZeroFormatResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class FormatHandlerTest extends TestCase
{
    public function testConstructionThrowsWhenNoFormatsAreDefined(): void
    {
        // Expect
        $this->expectExceptionObject(new NoDefinedFormatsException(ZeroFormatResource::class));

        // Act
        new FormatHandler(resolve(FormatRegistrar::class), ZeroFormatResource::class);
    }

    public function testSelectedIsNullBeforeSelectIsCalled(): void
    {
        // Arrange
        $handler = new FormatHandler(resolve(FormatRegistrar::class), BasicResource::class);

        // Act
        $actual = $handler->selected();

        // Assert
        $this->assertNull($actual);
    }

    public function testSelectThrowsWhenFormatIsUndefined(): void
    {
        // Arrange
        $handler = new FormatHandler(resolve(FormatRegistrar::class), BasicResource::class);

        // Expect
        $this->expectExceptionObject(new UndefinedFormatException(BasicResource::class, 'missing'));

        // Act
        $handler->select('missing');
    }

    #[DataProvider('selectSucceedsProvider')]
    public function testSelectSucceeds(
        string $resourceClass,
        string $formatName,
        FormatEntry $expected,
    ): void {
        // Arrange
        $handler = new FormatHandler(resolve(FormatRegistrar::class), $resourceClass);

        // Act
        $handler->select($formatName);

        // Assert
        $this->assertEquals($expected, $handler->selected());
    }

    public static function selectSucceedsProvider(): array
    {
        return [
            'BasicResource - base' => [
                'resourceClass' => BasicResource::class,
                'formatName' => 'base',
                'expected' => new FormatEntry('base', new ReflectionMethod(BasicResource::class, 'base')),
            ],
            'MultiFormatResource - alt' => [
                'resourceClass' => MultiFormatResource::class,
                'formatName' => MultiFormatResource::ALT,
                'expected' => new FormatEntry(MultiFormatResource::ALT, new ReflectionMethod(MultiFormatResource::class, 'withRequestFormat')),
            ],
            'MultiFormatResource - arrayable' => [
                'resourceClass' => MultiFormatResource::class,
                'formatName' => MultiFormatResource::ARRAYABLE,
                'expected' => new FormatEntry(MultiFormatResource::ARRAYABLE, new ReflectionMethod(MultiFormatResource::class, 'arrayable')),
            ],
            'MultiFormatResource - base' => [
                'resourceClass' => MultiFormatResource::class,
                'formatName' => MultiFormatResource::BASE,
                'expected' => new FormatEntry(MultiFormatResource::BASE, new ReflectionMethod(MultiFormatResource::class, 'baseFormat')),
            ],
            'MultiFormatResource - jsonSerializable' => [
                'resourceClass' => MultiFormatResource::class,
                'formatName' => MultiFormatResource::JSON_SERIALIZABLE,
                'expected' => new FormatEntry(MultiFormatResource::JSON_SERIALIZABLE, new ReflectionMethod(MultiFormatResource::class, 'jsonSerializable')),
            ],
        ];
    }
}
