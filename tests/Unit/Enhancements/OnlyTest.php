<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Enhancements;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\User;
use Sourcetoad\EnhancedResources\Tests\UserCollection;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class OnlyTest extends TestCase
{
    public function testOnlyEnhancementProperlyLimitsData(): void
    {
        # Arrange
        $user = new User(
            [
                'email_address' => 'john.doe@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'password' => Hash::make('correct-horse-battery-staple')
            ]
        );

        # Act
        $result = UserResource::make($user)
            ->only('first_name', 'last_name')
            ->resolve();

        # Assert
        $this->assertArrayNotHasKey('email_address', $result);
        $this->assertArrayNotHasKey('password', $result);
    }

    public function testAnonymousCollectionsCanLimitData(): void
    {
        # Arrange
        $user1 = new User(
            [
                'email_address' => 'john.doe@example.com',
                'first_name'    => 'John',
                'last_name'     => 'Doe',
                'password'      => Hash::make('correct-horse-battery-staple')
            ]
        );
        $user2 = new User(
            [
                'email_address' => 'jane.doe@example.com',
                'first_name'    => 'Jane',
                'last_name'     => 'Doe',
                'password'      => Hash::make('staple-battery-horse-correct')
            ]
        );

        # Act
        $result = UserResource::collection([1 => $user1, 2 => $user2])
            ->only('first_name', 'last_name')
            ->resolve();

        # Assert
        $this->assertArrayNotHasKey('email_address', $result[0]);
        $this->assertArrayNotHasKey('email_address', $result[1]);
        $this->assertArrayNotHasKey('password', $result[0]);
        $this->assertArrayNotHasKey('password', $result[1]);
    }

    public function testCollectionsCanLimitData(): void
    {
        # Arrange
        $user1 = new User(
            [
                'email_address' => 'john.doe@example.com',
                'first_name'    => 'John',
                'last_name'     => 'Doe',
                'password'      => Hash::make('correct-horse-battery-staple')
            ]
        );
        $user2 = new User(
            [
                'email_address' => 'jane.doe@example.com',
                'first_name'    => 'Jane',
                'last_name'     => 'Doe',
                'password'      => Hash::make('staple-battery-horse-correct')
            ]
        );

        # Act
        $result = UserCollection::make([1 => $user1, 2 => $user2])
            ->only('first_name', 'last_name')
            ->resolve();

        # Assert
        $this->assertArrayNotHasKey('email_address', $result[0]);
        $this->assertArrayNotHasKey('email_address', $result[1]);
        $this->assertArrayNotHasKey('password', $result[0]);
        $this->assertArrayNotHasKey('password', $result[1]);
    }

    public function testPaginatedCollectionsCanLimitData(): void
    {
        # Arrange
        $user1 = new User(
            [
                'email_address' => 'john.doe@example.com',
                'first_name'    => 'John',
                'last_name'     => 'Doe',
                'password'      => Hash::make('correct-horse-battery-staple')
            ]
        );
        $user2 = new User(
            [
                'email_address' => 'jane.doe@example.com',
                'first_name'    => 'Jane',
                'last_name'     => 'Doe',
                'password'      => Hash::make('staple-battery-horse-correct')
            ]
        );

        $paginator = new LengthAwarePaginator(
            [$user1, $user2],
            2,
            2
        );

        # Act
        $result = UserCollection::make($paginator)
            ->only('first_name', 'last_name')
            ->resolve();

        # Assert
        $this->assertArrayNotHasKey('email_address', $result[0]);
        $this->assertArrayNotHasKey('email_address', $result[1]);
        $this->assertArrayNotHasKey('password', $result[0]);
        $this->assertArrayNotHasKey('password', $result[1]);
    }
}
