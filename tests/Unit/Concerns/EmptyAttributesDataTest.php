<?php

namespace Sourcetoad\EnhancedResources\Tests\Unit\Concerns;

use Orchestra\Testbench\TestCase;
use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\Tests\User;

class EmptyAttributesDataTest extends TestCase
{
    /** @var array */
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = [];
    }

    public function testEmptyArrayWorksWithExcludes()
    {
        $data = EnhancedResource::make($this->model)
            ->exclude([])
            ->resolve();

        $this->assertEquals([], $data);
    }

    public function testEmptyArrayWorksWithIncludes()
    {
        $data = EnhancedResource::make($this->model)
            ->append([])
            ->resolve();

        $this->assertEquals([], $data);
    }

    public function testBothExcludingAndIncludeEmptyArray()
    {
        $data = EnhancedResource::make($this->model)
            ->exclude([])
            ->append([])
            ->resolve();

        $this->assertEquals([], $data);
    }
}
