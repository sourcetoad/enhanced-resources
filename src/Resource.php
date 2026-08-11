<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Override;

/**
 * @template TResource
 *
 * @property-read TResource $resource
 */
abstract class Resource extends JsonResource
{
    /**
     * @param TResource $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * @param iterable<array-key, TResource>|AbstractPaginator<array-key, TResource>|AbstractCursorPaginator<array-key, TResource> $resource
     * @return AnonymousResourceCollection<TResource>
     */
    #[Override]
    public static function collection($resource): AnonymousResourceCollection
    {
        /** @var AnonymousResourceCollection<TResource> */
        return parent::collection($resource);
    }

    /**
     * @param TResource $parameters
     */
    #[Override]
    public static function make(mixed $parameters = null, mixed ...$rest): static
    {
        if (func_num_args() === 0) {
            return parent::make();
        }

        return parent::make($parameters, ...$rest);
    }

    /**
     * @param iterable<array-key, TResource>|AbstractPaginator<array-key, TResource>|AbstractCursorPaginator<array-key, TResource> $resource
     * @return AnonymousResourceCollection<TResource>
     */
    #[Override]
    protected static function newCollection($resource): AnonymousResourceCollection
    {
        return new AnonymousResourceCollection($resource, static::class);
    }
}
