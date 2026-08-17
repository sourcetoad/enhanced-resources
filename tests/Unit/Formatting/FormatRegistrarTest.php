<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Formatting;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Sourcetoad\EnhancedResources\Formatting\FormatEntry;
use Sourcetoad\EnhancedResources\Formatting\FormatNameCollisionException;
use Sourcetoad\EnhancedResources\Formatting\FormatRegistrar;
use Sourcetoad\EnhancedResources\Formatting\NoDefinedFormatsException;
use Sourcetoad\EnhancedResources\Formatting\UnregisteredClassException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\MultiFormatResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\NameCollisionResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\ZeroFormatResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class FormatRegistrarTest extends TestCase
{
    public function testRegisterThrowsWhenNoFormatsAreDefined(): void
    {
        // Expect
        $this->expectExceptionObject(new NoDefinedFormatsException(ZeroFormatResource::class));

        // Act
        resolve(FormatRegistrar::class)->register(ZeroFormatResource::class);
    }

    public function testRegisterThrowsWhenNameCollisionOccurs(): void
    {
        // Expect
        $this->expectExceptionObject(new FormatNameCollisionException(
            NameCollisionResource::class,
            [
                NameCollisionResource::BASE => [
                    new FormatEntry(NameCollisionResource::BASE, new ReflectionMethod(NameCollisionResource::class, 'base')),
                    new FormatEntry(NameCollisionResource::BASE, new ReflectionMethod(NameCollisionResource::class, 'baseFormat')),
                ],
            ],
        ));

        // Act
        resolve(FormatRegistrar::class)->register(NameCollisionResource::class);
    }

    #[DataProvider('registerSuccessProvider')]
    public function testRegisterSucceeds(
        string $resourceClass,
        array $expected,
    ): void {
        // Act
        $actual = resolve(FormatRegistrar::class)->register($resourceClass);

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public static function registerSuccessProvider(): array
    {
        return [
            'BasicResource' => [
                'resourceClass' => BasicResource::class,
                'expected' => [
                    'base' => new FormatEntry('base', new ReflectionMethod(BasicResource::class, 'base')),
                ],
            ],
            'MultiFormatResource' => [
                'resourceClass' => MultiFormatResource::class,
                'expected' => [
                    MultiFormatResource::ARRAYABLE => new FormatEntry(MultiFormatResource::ARRAYABLE, new ReflectionMethod(MultiFormatResource::class, 'arrayable')),
                    MultiFormatResource::BASE => new FormatEntry(MultiFormatResource::BASE, new ReflectionMethod(MultiFormatResource::class, 'baseFormat')),
                    MultiFormatResource::JSON_SERIALIZABLE => new FormatEntry(MultiFormatResource::JSON_SERIALIZABLE, new ReflectionMethod(MultiFormatResource::class, 'jsonSerializable')),
                    MultiFormatResource::ALT => new FormatEntry(MultiFormatResource::ALT, new ReflectionMethod(MultiFormatResource::class, 'withRequestFormat')),
                ],
            ],
        ];
    }

    public function testResolveThrowsWhenClassHasNotBeenRegistered(): void
    {
        // Expect
        $this->expectExceptionObject(new UnregisteredClassException(BasicResource::class));

        // Act
        resolve(FormatRegistrar::class)->resolve(BasicResource::class, 'base');
    }

    #[DataProvider('resolveSuccessProvider')]
    public function testResolveSucceeds(
        string $resourceClass,
        string $formatName,
        FormatEntry $expected,
    ): void {
        // Arrange
        $registrar = resolve(FormatRegistrar::class);
        $registrar->register($resourceClass);

        // Act
        $actual = $registrar->resolve($resourceClass, $formatName);

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public static function resolveSuccessProvider(): array
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
