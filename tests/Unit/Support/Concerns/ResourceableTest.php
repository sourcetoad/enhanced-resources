<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Support\Concerns;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\User;
use Sourcetoad\EnhancedResources\Tests\UserCollection;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class ResourceableTest extends TestCase
{
    public function testToResourceProvidesAGenericEnhancedResourceByDefault(): void
    {
        # Arrange
        $model = new User;

        # Act
        $resource = $model->toResource();

        # Assert
        $this->assertInstanceOf(EnhancedResource::class, $resource);
    }

    public function testToResourceProvidesTheStaticResourceClassWhenOneIsNotProvider(): void
    {
        # Arrange
        User::$resourceClass = UserResource::class;
        $model = new User;

        # Act
        $resource = $model->toResource();

        # Assert
        $this->assertInstanceOf(UserResource::class, $resource);
    }

    public function testToResourceProvidesTheSpecifiedResourceClassWhenOneIsProvided(): void
    {
        # Arrange
        User::$resourceClass = null;
        $model = new User;

        # Act
        $resource = $model->toResource(UserResource::class);

        # Assert
        $this->assertInstanceOf(UserResource::class, $resource);
    }
}
