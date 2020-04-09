<?php

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use InvalidArgumentException;
use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\Tests\ExampleEnhancement;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class RegistrationTest extends TestCase
{
    public function testItCanRegisterAnEnhancement(): void
    {
        # Act
        EnhancedResource::enhance('example', ExampleEnhancement::class);

        # Assert
        $this->assertTrue(
            EnhancedResource::hasEnhancement('example'),
            'The enhancement was not registered.'
        );
    }

    public function testItCanRegisterCallableEnhancements(): void
    {
        # Act
        EnhancedResource::enhance('example', function() {});

        # Assert
        $this->assertTrue(
            EnhancedResource::hasEnhancement('example'),
            'The enhancement was not registered.'
        );
    }

    public function testItThrowsAnExceptionWhenProvidedAnInvalidEnhancement(): void
    {
        # Expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid enhancement.');

        # Act
        EnhancedResource::enhance('example', 2);
    }

    public function testItInheritsEnhancements(): void
    {
        # Act
        EnhancedResource::enhance('example', ExampleEnhancement::class);

        # Assert
        $this->assertTrue(
            UserResource::hasEnhancement('example'),
            'The enhancement was not inherited.'
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $reflectionClass = new \ReflectionClass(EnhancedResource::class);
        $reflectionProperty = $reflectionClass->getProperty('enhancements');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);
        $reflectionProperty->setAccessible(false);
    }
}