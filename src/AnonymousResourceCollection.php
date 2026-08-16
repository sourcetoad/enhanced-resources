<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection as IlluminateAnonymousResourceCollection;
use Traversable;

/**
 * @template TContent
 * @phpstan-import-type TCollectionSource from Resource as TSource
 */
class AnonymousResourceCollection extends IlluminateAnonymousResourceCollection
{
    /**
     * @param TSource $resource
     * @param class-string<Resource<TContent>> $collects
     */
    public function __construct($resource, $collects)
    {
        if (!is_a($collects, Resource::class, true)) {
            throw new MustCollectEnhancedResourcesException(
                static::class,
                $collects,
            );
        }

        parent::__construct($resource, $collects);
    }

    /**
     * @return Traversable<array-key, TContent>
     */
    public function getIterator(): Traversable
    {
        return parent::getIterator();
    }
}
