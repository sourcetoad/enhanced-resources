<?php

namespace Sourcetoad\EnhancedResources\Tests\Unit\Enhancements;

use Sourcetoad\EnhancedResources\Enhancements\ExcludeEnhancement;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\User;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class ExcludeEnhancementTest extends TestCase
{
    public function testItDoesNotReturnTheExcludedField(): void
    {
        # Arrange
        UserResource::enhance('exclude', ExcludeEnhancement::class);
        $user = new User([
            'email_address' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        # Act
        $result = UserResource::make($user)
            ->exclude(['email_address'])
            ->resolve();

        # Assert
        $this->assertArrayNotHasKey('email_address', $result);
    }
}