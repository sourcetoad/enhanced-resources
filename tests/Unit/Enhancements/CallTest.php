<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit\Enhancements;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\User;
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

}
