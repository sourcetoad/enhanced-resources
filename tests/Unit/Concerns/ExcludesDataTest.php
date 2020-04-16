<?php

namespace Sourcetoad\EnhancedResources\Tests\Unit\Concerns;

use Orchestra\Testbench\TestCase;
use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\Tests\User;

class ExcludesDataTest extends TestCase
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

    public function testSingleAttributeIsExcluded()
    {
        $data = EnhancedResource::make($this->model)
            ->exclude('password')
            ->resolve();

        $this->assertArrayNotHasKey('password', $data);
    }

    public function testChainedAttributesAreExcluded()
    {
        $data = EnhancedResource::make($this->model)
            ->exclude('email_address')
            ->exclude('password')
            ->resolve();

        $this->assertArrayNotHasKey('email_address', $data);
        $this->assertArrayNotHasKey('password', $data);
    }

    public function testArrayOfAttributesAreExcluded()
    {
        $data = EnhancedResource::make($this->model)
            ->exclude(['email_address', 'password'])
            ->resolve();

        $this->assertArrayNotHasKey('email_address', $data);
        $this->assertArrayNotHasKey('password', $data);
    }

    public function testChainedArrayOfAttributesAreExcluded()
    {
        $data = EnhancedResource::make($this->model)
            ->exclude(['first_name', 'last_name'])
            ->exclude(['email_address', 'password'])
            ->resolve();

        $this->assertArrayNotHasKey('email_address', $data);
        $this->assertArrayNotHasKey('first_name', $data);
        $this->assertArrayNotHasKey('last_name', $data);
        $this->assertArrayNotHasKey('password', $data);
    }

    public function testAttributesNotIncludedInOnlyAreExcluded()
    {
        $data = EnhancedResource::make($this->model)
            ->only('id')
            ->resolve();

        $this->assertArrayHasKey('id', $data);
        $this->assertCount(1, $data);
    }

    public function testOnlyAttributesNotIncludedInLastOnlyCallAreExcluded()
    {
        $data = EnhancedResource::make($this->model)
            ->only('id')
            ->only('email_address')
            ->resolve();

        $this->assertArrayHasKey('email_address', $data);
        $this->assertCount(1, $data);
    }

    public function testAttributesNotIncludedInOnlyArrayAreExcluded()
    {
        $data = EnhancedResource::make($this->model)
            ->only(['email_address', 'id'])
            ->resolve();

        $this->assertArrayHasKey('email_address', $data);
        $this->assertArrayHasKey('id', $data);
        $this->assertCount(2, $data);
    }
}
