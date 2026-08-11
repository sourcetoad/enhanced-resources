<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection as IlluminateAnonymousResourceCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Override;
use Traversable;

/**
 * @template TResource
 *
 * @property-read Collection<array-key, Resource<TResource>>|null $collection
 */
class AnonymousResourceCollection extends IlluminateAnonymousResourceCollection
{
    /**
     * @param iterable<array-key, TResource>|AbstractPaginator<array-key, TResource>|AbstractCursorPaginator<array-key, TResource> $resource
     * @param class-string<Resource<TResource>> $collects
     */
    public function __construct($resource, string $collects)
    {
        if (!is_a($collects, Resource::class, true)) {
            throw new InvalidCollectsException(static::class, $collects);
        }

        parent::__construct($resource, $collects);
    }

    /**
     * @return Traversable<array-key, Resource<TResource>>
     */
    #[Override]
    public function getIterator(): Traversable
    {
        return parent::getIterator();
    }
}
