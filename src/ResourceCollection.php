<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\ResourceCollection as IlluminateResourceCollection;
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
class ResourceCollection extends IlluminateResourceCollection
{
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
