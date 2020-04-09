<?php

namespace Sourcetoad\EnhancedResources\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Sourcetoad\EnhancedResources\EnhancedResource;

abstract class TestCase extends OrchestraTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        $reflectionClass = new \ReflectionClass(EnhancedResource::class);
        $reflectionProperty = $reflectionClass->getProperty('enhancements');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);
        $reflectionProperty->setAccessible(false);
    }
}