<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Resourceable;

use Sourcetoad\EnhancedResources\Exceptions\NoResourceDefinedException;
use Sourcetoad\EnhancedResources\Resourceable\ConvertsToResource;
use Sourcetoad\EnhancedResources\Tests\ConvertibleObject;
use Sourcetoad\EnhancedResources\Tests\ImplicitDefaultResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class ConvertsToResourceTest extends TestCase
{
    public function test_object_can_be_converted_to_a_resource(): void
    {
        # Arrange
        $object = new ConvertibleObject(1, 'John', 'Doe');

        # Act
        $actualResource = $object->toResource();

        # Assert
        $this->assertInstanceOf(ImplicitDefaultResource::class, $actualResource);
    }

    public function test_object_cannot_be_converted_if_no_resource_is_specified(): void
    {
        # Expect
        $this->expectException(NoResourceDefinedException::class);

        # Arrange
        $object = new class {
            use ConvertsToResource;
        };

        # Act
        $object->toResource();
    }
}
