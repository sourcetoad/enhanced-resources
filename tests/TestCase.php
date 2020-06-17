<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Sourcetoad\EnhancedResources\ServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class
        ];
    }
}
