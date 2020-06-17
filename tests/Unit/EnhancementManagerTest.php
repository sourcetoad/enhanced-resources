<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Sourcetoad\EnhancedResources\EnhancementManager;
use Sourcetoad\EnhancedResources\Tests\AdminUserResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use Sourcetoad\EnhancedResources\Tests\UserResource;

class EnhancementManagerTest extends TestCase
{
    protected EnhancementManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = new EnhancementManager;
    }

    public function testEnhancementsCanBeRegisteredGlobally(): void
    {
        # Act
        $this->manager->register('example', fn() => null);

        # Assert
        $this->assertTrue($this->manager->hasEnhancement('example'));
    }

    public function testEnhancementsCanBeRegisteredToSpecificResources(): void
    {
        # Act
        $this->manager->register('example', fn() => null, UserResource::class);

        # Assert
        $this->assertFalse($this->manager->hasEnhancement('example'));
        $this->assertTrue($this->manager->hasEnhancement('example', UserResource::class));
    }

    public function testEnhancementsAreInherited(): void
    {
        # Act
        $this->manager->register('example', fn() => null);

        # Assert
        $this->assertTrue($this->manager->hasEnhancement('example'));
        $this->assertTrue($this->manager->hasEnhancement('example', UserResource::class));
        $this->assertTrue($this->manager->hasEnhancement('example', AdminUserResource::class));
    }

    public function testEnhancementsCanBeOverwrittenOnDescendents(): void
    {
        # Act
        $this->manager->register('example', fn() => 1);
        $this->manager->register('example', fn() => 2, UserResource::class);

        # Assert
        $this->assertNotSame(
            $this->manager->getEnhancement('example'),
            $this->manager->getEnhancement('example', UserResource::class)
        );
    }

    public function testEnhancementsAreInheritedFromNearestAncestor(): void
    {
        # Act
        // Registering descendant enhancement first to ensure that
        // inheritance order does not depend on registration order.
        $this->manager->register('example', fn() => 2, UserResource::class);
        $this->manager->register('example', fn() => 1);

        # Assert
        $this->assertSame(
            $this->manager->getEnhancement('example', UserResource::class),
            $this->manager->getEnhancement('example', AdminUserResource::class)
        );
    }
}
