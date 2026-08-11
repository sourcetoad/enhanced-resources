<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Sourcetoad\EnhancedResources\AnonymousResourceCollection;
use Sourcetoad\EnhancedResources\InvalidCollectsException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\ConcreteResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\PlainJsonResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;
use stdClass;

final class AnonymousResourceCollectionTest extends TestCase
{
    public function testConstructWithCollectsExtendingResourceSucceeds(): void
    {
        // Arrange
        $items = [new stdClass, new stdClass];

        // Act
        $collection = new AnonymousResourceCollection($items, ConcreteResource::class);

        // Assert
        $this->assertContainsOnlyInstancesOf(ConcreteResource::class, iterator_to_array($collection->getIterator()));
    }

    public function testConstructWithCollectsNotExtendingResourceThrows(): void
    {
        try {
            // Act
            new AnonymousResourceCollection([new stdClass], PlainJsonResource::class);
            $this->fail(sprintf('Expected %s to be thrown, but it was not.', InvalidCollectsException::class));
        } catch (InvalidCollectsException $exception) {
            // Assert
            $this->assertSame([
                'collectionClass' => AnonymousResourceCollection::class,
                'collects' => PlainJsonResource::class,
            ], $exception->context());
        }
    }
}
