<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Enhancements;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\User;
use Sourcetoad\EnhancedResources\Tests\UserCollection;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class AppendTest extends TestCase
{
    public function testAppendEnhancementProperlyAddsData(): void
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
            ->append('name')
            ->resolve();

        # Assert
        $this->assertArrayHasKey('name', $result);
        $this->assertSame('John Doe', $result['name']);
    }

    public function testAnonymousCollectionsCanAppendData(): void
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
            ->append('name')
            ->resolve();

        # Assert
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('name', $result[1]);
    }

    public function testCollectionsCanAppendData(): void
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
            ->append('name')
            ->resolve();

        # Assert
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('name', $result[1]);
    }

    public function testPaginatedCollectionsCanAppendData(): void
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
            ->append('name')
            ->resolve();

        # Assert
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('name', $result[1]);
    }
}
