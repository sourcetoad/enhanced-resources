<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as IlluminateResourceCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use JsonSerializable;
use Override;
use Sourcetoad\EnhancedResources\Formatting\InteractsWithFormatting;
use Traversable;

/**
 * @template TResource
 *
 * @property-read Collection<array-key, Resource<TResource>>|null $collection
 */
class ResourceCollection extends IlluminateResourceCollection
{
    use InteractsWithFormatting;

    /**
     * @var class-string<Resource<TResource>>
     */
    public $collects;

    /**
     * @param iterable<array-key, TResource>|AbstractPaginator<array-key, TResource>|AbstractCursorPaginator<array-key, TResource> $resource
     */
    public function __construct($resource)
    {
        $collects = $this->collects();

        if ($collects === null || !is_a($collects, Resource::class, true)) {
            throw new InvalidCollectsException(static::class, $collects);
        }

        parent::__construct($resource);

        $this->initializeFormatting($collects);
    }

    /**
     * @return Traversable<array-key, Resource<TResource>>
     */
    #[Override]
    public function getIterator(): Traversable
    {
        return parent::getIterator();
    }

    /**
     * @return array<array-key, mixed>|Arrayable<array-key, mixed>|JsonSerializable
     */
    public function toArray(Request $request): array|Arrayable|JsonSerializable
    {
        $currentFormat = $this->formatHandler->current();
        if ($currentFormat && $this->collection) {
            $this->collection->each(function (Resource $resource) use ($currentFormat) {
                $resource->format($currentFormat->name);
            });
        }

        return parent::toArray($request);
    }
}
