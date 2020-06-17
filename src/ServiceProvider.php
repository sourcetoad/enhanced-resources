<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Sourcetoad\EnhancedResources\Contracts\EnhancementManager as EnhancementManagerContract;
use Sourcetoad\EnhancedResources\Enhancements\Append;
use Sourcetoad\EnhancedResources\Enhancements\Call;
use Sourcetoad\EnhancedResources\Enhancements\Exclude;
use Sourcetoad\EnhancedResources\Enhancements\Only;
use Sourcetoad\EnhancedResources\Enhancements\Replace;
use Sourcetoad\EnhancedResources\Support\Facades\ResourceEnhancements;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot(): void
    {
        ResourceEnhancements::register(
            'append',
            new Append,
        );
        ResourceEnhancements::register(
            'call',
            new Call
        );
        ResourceEnhancements::register(
            'exclude',
            new Exclude
        );
        ResourceEnhancements::register(
            'only',
            new Only
        );
        ResourceEnhancements::register(
            'replace',
            new Replace
        );
    }

    public function register(): void
    {
        $this->app->singleton(
            EnhancementManagerContract::class,
            EnhancementManager::class
        );
    }
}
