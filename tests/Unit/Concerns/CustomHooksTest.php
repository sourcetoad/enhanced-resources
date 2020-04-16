<?php

namespace Sourcetoad\EnhancedResources\Tests\Unit\Concerns;

use Orchestra\Testbench\TestCase;
use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\Tests\User;

class CustomHooksTest extends TestCase
{
    /** @var User */
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new User([
            'email_address' => 'test@example.com',
            'first_name' => 'John',
            'id' => 1,
            'last_name' => 'Doe',
            'password' => 'CorrectBatteryHorseStaple'
        ]);
    }

    public function testCustomHookAppliesToResource()
    {
        $testValue = 'TEST';

        $data = EnhancedResource::make($this->model)
            ->customHook(function (EnhancedResource $resource, array $data) use ($testValue) {
                $data['from_custom_hook'] = $testValue;

                return $data;
            })
            ->resolve();

        $this->assertArrayHasKey('from_custom_hook', $data);
    }
}
