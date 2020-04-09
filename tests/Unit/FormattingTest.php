<?php

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use BadMethodCallException;
use Illuminate\Support\Facades\Hash;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\User;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class FormattingTest extends TestCase
{
    public function testItDefaultsToUsingTheUnderlyingToArrayFunctionality(): void
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

    public function testItUsesTheFormatProvidedDuringInstantiation(): void
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
                'name' => 'John Doe'
            ],
            $result
        );
    }

    public function testItThrowsAnExceptionIfMethodDoesNotExistForProvidedFormat(): void
    {
        # Expect
        $this->expectException(BadMethodCallException::class);

        # Arrange
        $user = new User([
            'email_address' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'password' => Hash::make('correct-horse-battery-staple')
        ]);
        $resource = UserResource::make($user, 'non-existent');

        # Act
        $resource->resolve();
    }
}