<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use ArgumentCountError;
use Sourcetoad\EnhancedResources\AnonymousResourceCollection;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicContent;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class ResourceTest extends TestCase
{
    public function testThrowsWhenMadeWithNoArguments(): void
    {
        // Expect
        $this->expectException(ArgumentCountError::class);

        // Act
        BasicResource::make();
    }

    public function testCanBeConstructedManually(): void
    {
        // Act
        $resource = new BasicResource(new BasicContent('test'));

        // Assert
        $this->assertSame('test', $resource->resource->content);
    }

    public function testCanBeMadeSuccessfully(): void
    {
        // Act
        $resource = BasicResource::make(new BasicContent('test'));

        // Assert
        $this->assertSame('test', $resource->resource->content);
    }

    public function testCanCollectMultiple(): void
    {
        // Act
        $collection = BasicResource::collection([new BasicContent(1), new BasicContent(2)]);

        // Assert
        $this->assertInstanceOf(AnonymousResourceCollection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertContainsOnlyInstancesOf(BasicResource::class, $collection->collection);
    }
}
