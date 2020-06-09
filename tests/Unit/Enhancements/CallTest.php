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

class CallTest extends TestCase
{
    public function testCallEnhancementCallsProvidedCallable(): void
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
            ->call(
                fn(EnhancedResource $resource, array $data, ...$keys) => Arr::except($data, $keys),
                'password'
            )
            ->resolve();

        # Assert
        $this->assertArrayNotHasKey('password', $result);
    }

    public function testAnonymousCollectionsCanCallProvidedCallable(): void
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
            ->call(
                fn(EnhancedResource $resource, array $data, ...$keys) => Arr::except($data, $keys),
                'password'
            )
            ->resolve();

        # Assert
        $this->assertArrayNotHasKey('password', $result[0]);
        $this->assertArrayNotHasKey('password', $result[1]);
    }

    public function testCollectionsCanCallProvidedCallable(): void
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
            ->call(
                fn(EnhancedResource $resource, array $data, ...$keys) => Arr::except($data, $keys),
                'password'
            )
            ->resolve();

        # Assert
        $this->assertArrayNotHasKey('password', $result[0]);
        $this->assertArrayNotHasKey('password', $result[1]);
    }

    public function testPaginatedCollectionsCanCallProvidedCallable(): void
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
            ->call(
                fn(EnhancedResource $resource, array $data, ...$keys) => Arr::except($data, $keys),
                'password'
            )
            ->resolve();

        # Assert
        $this->assertArrayNotHasKey('password', $result[0]);
        $this->assertArrayNotHasKey('password', $result[1]);
    }
}
