<?php

namespace Sourcetoad\EnhancedResources\Tests\Unit\Concerns;

use Orchestra\Testbench\TestCase;
use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\Tests\User;

class IncludesDataTest extends TestCase
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

    public function testSingleAttributeIsAppended()
    {
        $data = EnhancedResource::make($this->model)
            ->append('full_name')
            ->resolve();

        $this->assertArrayHasKey('full_name', $data);
    }

    public function testChainedAttributesAreAppended()
    {
        $data = EnhancedResource::make($this->model)
            ->append('full_name')
            ->append('first_initial')
            ->append('last_initial')
            ->resolve();

        $this->assertArrayHasKey('full_name', $data);
        $this->assertArrayHasKey('first_initial', $data);
        $this->assertArrayHasKey('last_initial', $data);
    }

    public function testArrayOfAttributesAreAppended()
    {
        $data = EnhancedResource::make($this->model)
            ->append(['first_initial', 'last_initial'])
            ->resolve();

        $this->assertArrayHasKey('first_initial', $data);
        $this->assertArrayHasKey('last_initial', $data);
    }

    public function testChainedArrayOfAttributesAreAppended()
    {
        $data = EnhancedResource::make($this->model)
            ->append(['first_initial', 'last_initial'])
            ->append(['full_name'])
            ->resolve();

        $this->assertArrayHasKey('full_name', $data);
        $this->assertArrayHasKey('first_initial', $data);
        $this->assertArrayHasKey('last_initial', $data);
    }
}
