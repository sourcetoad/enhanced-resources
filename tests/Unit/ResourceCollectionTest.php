<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Sourcetoad\EnhancedResources\InvalidCollectsException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\ConcreteResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\ConcreteResourceCollection;
use Sourcetoad\EnhancedResources\Tests\Fixtures\InvalidCollectsResourceCollection;
use Sourcetoad\EnhancedResources\Tests\Fixtures\PlainJsonResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\UnresolvableCollectsResourceCollection;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use stdClass;

final class ResourceCollectionTest extends TestCase
{
    public function testConstructWithResolvableCollectsSucceeds(): void
    {
        // Arrange
        $items = [new stdClass, new stdClass];

        // Act
        $collection = new ConcreteResourceCollection($items);

        // Assert
        $this->assertContainsOnlyInstancesOf(ConcreteResource::class, iterator_to_array($collection->getIterator()));
    }

    public function testConstructWithUnresolvableCollectsThrows(): void
    {
        try {
            // Act
            new UnresolvableCollectsResourceCollection([new stdClass]);
            $this->fail(sprintf('Expected %s to be thrown, but it was not.', InvalidCollectsException::class));
        } catch (InvalidCollectsException $exception) {
            // Assert
            $this->assertSame([
                'collectionClass' => UnresolvableCollectsResourceCollection::class,
                'collects' => null,
            ], $exception->context());
        }
    }

    public function testConstructWithCollectsNotExtendingResourceThrows(): void
    {
        try {
            // Act
            new InvalidCollectsResourceCollection([new stdClass]);
            $this->fail(sprintf('Expected %s to be thrown, but it was not.', InvalidCollectsException::class));
        } catch (InvalidCollectsException $exception) {
            // Assert
            $this->assertSame([
                'collectionClass' => InvalidCollectsResourceCollection::class,
                'collects' => PlainJsonResource::class,
            ], $exception->context());
        }
    }
}
