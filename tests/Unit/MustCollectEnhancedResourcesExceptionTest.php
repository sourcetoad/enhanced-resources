<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Illuminate\Http\Resources\Json\JsonResource;
use Sourcetoad\EnhancedResources\AnonymousResourceCollection;
use Sourcetoad\EnhancedResources\MustCollectEnhancedResourcesException;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class MustCollectEnhancedResourcesExceptionTest extends TestCase
{
    public function testExposesCorrectData(): void
    {
        // Act
        $exception = new MustCollectEnhancedResourcesException(AnonymousResourceCollection::class, JsonResource::class);

        // Assert
        $this->assertSame(AnonymousResourceCollection::class, $exception->resourceCollectionClass);
        $this->assertSame(JsonResource::class, $exception->actuallyCollects);
        $this->assertSame([
            'actuallyCollects' => JsonResource::class,
            'resourceCollectionClass' => AnonymousResourceCollection::class,
        ], $exception->context());
    }
}
