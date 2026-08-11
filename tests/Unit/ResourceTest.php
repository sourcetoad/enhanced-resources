<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use ArgumentCountError;
use Sourcetoad\EnhancedResources\Tests\Fixtures\ConcreteResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use stdClass;

final class ResourceTest extends TestCase
{
    public function testCollectionReturnsAnonymousResourceCollectionOfCallingClass(): void
    {
        // Arrange
        $items = [new stdClass, new stdClass];

        // Act
        $collection = ConcreteResource::collection($items);

        // Assert
        $this->assertSame(ConcreteResource::class, $collection->collects);
        $this->assertContainsOnlyInstancesOf(ConcreteResource::class, iterator_to_array($collection->getIterator()));
    }

    public function testMakeWithNoArgumentsThrowsBecauseResourceIsRequired(): void
    {
        // Expect
        $this->expectException(ArgumentCountError::class);

        // Act
        ConcreteResource::make();
    }

    public function testMakeWithExplicitNullCreatesResourceWrappingNull(): void
    {
        // Act
        $resource = ConcreteResource::make(null);

        // Assert
        $this->assertNull($resource->resource);
    }

    public function testMakeWithResourceCreatesResourceWrappingGivenValue(): void
    {
        // Arrange
        $item = new stdClass;

        // Act
        $resource = ConcreteResource::make($item);

        // Assert
        $this->assertSame($item, $resource->resource);
    }
}
