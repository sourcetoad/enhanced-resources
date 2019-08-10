<?php

namespace Jasonej\EnhancedResources\Tests\Unit;

use Jasonej\EnhancedResources\Tests\Person;
use Jasonej\EnhancedResources\Tests\Resource;
use Orchestra\Testbench\TestCase;

class CollectionTest extends TestCase
{
    protected $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = collect([
            new Person([
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'ssn' => '000-00-0000'
            ]),
            new Person([
                'id' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'ssn' => '000-00-0000'
            ])
        ]);
    }

    public function testAppends()
    {
        $resources = Resource::collection($this->data)
            ->append(['name'])
            ->resolve();

        foreach ($resources as $resource) {
            $this->assertArrayHasKey('name', $resource);
        }
    }

    public function testExcludes()
    {
        $resources = Resource::collection($this->data)
            ->exclude(['ssn'])
            ->resolve();

        foreach ($resources as $resource) {
            $this->assertArrayNotHasKey('ssn', $resource);
        }
    }

    public function testOnly(): void
    {
        $resources = Resource::collection($this->data)
            ->only(['first_name', 'last_name'])
            ->resolve();

        foreach ($resources as $resource) {
            $this->assertCount(2, $resource);
            $this->assertArrayHasKey('first_name', $resource);
            $this->assertArrayHasKey('last_name', $resource);
        }
    }
}