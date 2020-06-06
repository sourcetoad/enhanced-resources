<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Illuminate\Support\Facades\Hash;
use Sourcetoad\EnhancedResources\Exceptions\UndefinedFormatException;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\User;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class FormattingTest extends TestCase
{
    public function testDefaultFormatIsToArray(): void
    {
        # Arrange
        $user = new User([
            'email_address' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'password' => Hash::make('correct-horse-battery-staple')
        ]);
        $resource = UserResource::make($user);

        # Act
        $result = $resource->resolve();

        # Assert
        $this->assertEquals(
            $user->toArray(),
            $result
        );
    }

    public function testFormatCanBeSetDuringInitialization(): void
    {
        # Arrange
        $user = new User([
            'email_address' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'password' => Hash::make('correct-horse-battery-staple')
        ]);
        $resource = UserResource::make($user, 'alternative');

        # Act
        $result = $resource->resolve();

        # Assert
        $this->assertEquals(
            [
                'email_address' => 'john.doe@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe'
            ],
            $result
        );
    }

    public function testFormatCanBeSetAfterInitialization(): void
    {
        # Arrange
        $user = new User([
                             'email_address' => 'john.doe@example.com',
                             'first_name' => 'John',
                             'last_name' => 'Doe',
                             'password' => Hash::make('correct-horse-battery-staple')
                         ]);
        $resource = UserResource::make($user);
        $resource->format('alternative');

        # Act
        $result = $resource->resolve();

        # Assert
        $this->assertEquals(
            [
                'email_address' => 'john.doe@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe'
            ],
            $result
        );
    }

    public function testThrowsExceptionForUndefinedFormat(): void
    {
        # Expect
        $this->expectException(UndefinedFormatException::class);
        $this->expectExceptionMessage('No \'nonexistent\' format was defined for ' . UserResource::class);

        # Act
        UserResource::make([], 'nonexistent')->resolve();
    }
}
