<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Enhancements;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\User;
use Sourcetoad\EnhancedResources\Tests\UserCollection;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class ReplaceTest extends TestCase
{
    public function testReplaceEnhancementProperlyReplacesData(): void
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
            ->replace([
                'first_name' => 'Jane'
            ])
            ->resolve();

        # Assert
        $this->assertSame('Jane', $result['first_name']);
    }

    public function testAnonymousCollectionsCanReplaceData(): void
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
            ->replace([
                'first_name' => 'Jane'
            ])
            ->resolve();

        # Assert
        $this->assertSame('Jane', $result[0]['first_name']);
        $this->assertSame('Jane', $result[1]['first_name']);
    }

    public function testCollectionsCanReplaceData(): void
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
            ->replace([
                'first_name' => 'Jane'
            ])
            ->resolve();

        # Assert
        $this->assertSame('Jane', $result[0]['first_name']);
        $this->assertSame('Jane', $result[1]['first_name']);
    }

    public function testPaginatedCollectionsCanReplaceData(): void
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
            ->replace([
                'first_name' => 'Jane'
            ])
            ->resolve();

        # Assert
        $this->assertSame('Jane', $result[0]['first_name']);
        $this->assertSame('Jane', $result[1]['first_name']);
    }
}
