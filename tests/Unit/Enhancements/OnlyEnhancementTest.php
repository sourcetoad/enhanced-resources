<?php

namespace Sourcetoad\EnhancedResources\Tests\Unit\Enhancements;

use Sourcetoad\EnhancedResources\Enhancements\OnlyEnhancement;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\User;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class OnlyEnhancementTest extends TestCase
{
    public function testItDoesNotReturnTheExcludedField(): void
    {
        # Arrange
        UserResource::enhance('only', OnlyEnhancement::class);
        $user = new User([
            'email_address' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        # Act
        $result = UserResource::make($user)
            ->only(['first_name', 'last_name'])
            ->resolve();

        # Assert
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('first_name', $result);
        $this->assertArrayHasKey('last_name', $result);
    }
}