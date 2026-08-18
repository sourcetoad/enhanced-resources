<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Unit;

use ArgumentCountError;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionMethod;
use Sourcetoad\EnhancedResources\AnonymousResourceCollection;
use Sourcetoad\EnhancedResources\Formatting\FormatEntry;
use Sourcetoad\EnhancedResources\Formatting\FormatNotSelectedException;
use Sourcetoad\EnhancedResources\Formatting\InvalidFormatResultException;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\ArrayableResult;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicContent;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\BasicResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\InvalidFormatResource;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\JsonSerializableResult;
use Sourcetoad\EnhancedResources\Tests\Fixtures\Resources\MultiFormatResource;
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

    public function testToArrayThrowsWhenFormatIsNotSelected(): void
    {
        // Arrange
        $resource = new BasicResource(new BasicContent('test'));

        // Expect
        $this->expectExceptionObject(new FormatNotSelectedException);

        // Act
        $resource->toArray(new Request);
    }

    public function testToArrayThrowsWhenFormatResultIsInvalid(): void
    {
        // Arrange
        $resource = (new InvalidFormatResource(new BasicContent('test')))->format('base');

        // Expect
        $this->expectExceptionObject(new InvalidFormatResultException(
            InvalidFormatResource::class,
            new FormatEntry('base', new ReflectionMethod(InvalidFormatResource::class, 'base')),
            'string',
        ));

        // Act
        $resource->toArray(new Request);
    }

    #[DataProvider('toArraySucceedsProvider')]
    public function testToArraySucceeds(string $resourceClass, string $format, mixed $expected): void
    {
        // Arrange
        $resource = (new $resourceClass(new BasicContent('test')))->format($format);

        // Act
        $actual = $resource->toArray(new Request);

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public static function toArraySucceedsProvider(): array
    {
        return [
            'BasicResource - base' => [
                'resourceClass' => BasicResource::class,
                'format' => 'base',
                'expected' => ['content' => 'test'],
            ],
            'MultiFormatResource - base' => [
                'resourceClass' => MultiFormatResource::class,
                'format' => MultiFormatResource::BASE,
                'expected' => ['content' => 'test'],
            ],
            'MultiFormatResource - arrayable' => [
                'resourceClass' => MultiFormatResource::class,
                'format' => MultiFormatResource::ARRAYABLE,
                'expected' => new ArrayableResult(new BasicContent('test')),
            ],
            'MultiFormatResource - jsonSerializable' => [
                'resourceClass' => MultiFormatResource::class,
                'format' => MultiFormatResource::JSON_SERIALIZABLE,
                'expected' => new JsonSerializableResult(new BasicContent('test')),
            ],
        ];
    }

    public function testToArrayPassesRequestToFormatMethod(): void
    {
        // Arrange
        $resource = (new MultiFormatResource(new BasicContent('test')))->format(MultiFormatResource::ALT);
        $request = Request::create('/', 'GET', ['q' => 'search']);

        // Act
        $actual = $resource->toArray($request);

        // Assert
        $this->assertSame(['content' => 'test', 'query' => 'search'], $actual);
    }
}
