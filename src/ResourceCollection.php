<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\ResourceCollection as IlluminateResourceCollection;
use Traversable;

/**
 * @template TContent
 * @phpstan-import-type TCollectionSource from Resource as TSource
 */
abstract class ResourceCollection extends IlluminateResourceCollection
{
    /**
     * @param TSource $resource
     */
    public function __construct($resource)
    {
        $collects = $this->collects();

        if ($collects === null || !is_a($collects, Resource::class, true)) {
            throw new MustCollectEnhancedResourcesException(
                static::class,
                $collects ?? 'null',
            );
        }

        parent::__construct($resource);
    }

    /**
     * @return Traversable<array-key, TContent>
     */
    public function getIterator(): Traversable
    {
        return parent::getIterator();
    }
}
