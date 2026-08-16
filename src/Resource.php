<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Override;

/**
 * @template TContent
 * @phpstan-type TCollectionSource iterable<array-key, TContent>|AbstractCursorPaginator<array-key, TContent>|AbstractPaginator<array-key, TContent>
 */
abstract class Resource extends JsonResource
{
    /**
     * @param TContent $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * @param TCollectionSource $resource
     * @return AnonymousResourceCollection<TContent>
     */
    #[Override]
    public static function collection($resource): AnonymousResourceCollection
    {
        /** @var AnonymousResourceCollection<TContent> */
        return parent::collection($resource);
    }

    /**
     * @param TContent $parameters
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
     * @param TCollectionSource $resource
     * @return AnonymousResourceCollection<TContent>
     */
    #[Override]
    protected static function newCollection($resource): AnonymousResourceCollection
    {
        return new AnonymousResourceCollection($resource, static::class);
    }
}
