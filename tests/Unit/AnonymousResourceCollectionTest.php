<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Sourcetoad\EnhancedResources\AnonymousResourceCollection;
use Sourcetoad\EnhancedResources\Formatting\FormatNotSelectedException;
use Sourcetoad\EnhancedResources\MustCollectEnhancedResourcesException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicContent;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\MultiFormatResource;
use Sourcetoad\EnhancedResources\Tests\TestCase;

class AnonymousResourceCollectionTest extends TestCase
{
    public function testMustCollectEnhancedResources(): void
    {
        // Expect
        $this->expectExceptionObject(new MustCollectEnhancedResourcesException(
            AnonymousResourceCollection::class,
            JsonResource::class,
        ));

        // Act
        new AnonymousResourceCollection([], JsonResource::class);
    }

    public function testInstantiatesSuccessfully(): void
    {
        // Arrange
        $content = [new BasicContent(1), new BasicContent(2)];

        // Act
        $collection = new AnonymousResourceCollection($content, BasicResource::class);

        // Assert
        $data = iterator_to_array($collection->getIterator());
        $this->assertCount(2, $data);
        $this->assertContainsOnlyInstancesOf(BasicResource::class, $data);
    }

    public function testToArrayThrowsWhenFormatIsNotSelected(): void
    {
        // Arrange
        $collection = new AnonymousResourceCollection([new BasicContent(1), new BasicContent(2)], BasicResource::class);

        // Expect
        $this->expectExceptionObject(new FormatNotSelectedException);

        // Act
        $collection->toArray(new Request);
    }

    public function testToArraySelectsFormatOnEachItem(): void
    {
        // Arrange
        $collection = MultiFormatResource::collection([new BasicContent(1), new BasicContent(2)])
            ->format(MultiFormatResource::ARRAYABLE);

        // Act
        $actual = $collection->toArray(new Request);

        // Assert
        $this->assertEquals([
            ['content' => 1, 'type' => 'arrayable'],
            ['content' => 2, 'type' => 'arrayable'],
        ], $actual);
    }
}
