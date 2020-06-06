<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Sourcetoad\EnhancedResources\Contracts\EnhancementManager as EnhancementManagerContract;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            EnhancementManagerContract::class,
            EnhancementManager::class
        );
    }
}
