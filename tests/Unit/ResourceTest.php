<?php

namespace Jasonej\EnhancedResources\Tests\Unit;

use Jasonej\EnhancedResources\Tests\Person;
use Jasonej\EnhancedResources\Tests\Resource;
use Orchestra\Testbench\TestCase;

class ResourceTest extends TestCase
{
    /** @var Person */
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = new Person([
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'ssn' => '000-00-0000'
        ]);
    }

    public function testAppends(): void
    {
        $resource = Resource::make($this->model)
            ->append(['name'])
            ->resolve();

        $this->assertArrayHasKey('name', $resource);
    }

    public function testExcludes(): void
    {
        $resource = Resource::make($this->model)
            ->exclude(['ssn'])
            ->resolve();

        $this->assertArrayNotHasKey('ssn', $resource);
    }

    public function testOnly(): void
    {
        $resource = Resource::make($this->model)
            ->only(['first_name', 'last_name'])
            ->resolve();

        $this->assertCount(2, $resource);
        $this->assertArrayHasKey('first_name', $resource);
        $this->assertArrayHasKey('last_name', $resource);
    }
}