<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use JsonSerializable;
use Override;
use Sourcetoad\EnhancedResources\Formatting\FormatNotSelectedException;
use Sourcetoad\EnhancedResources\Formatting\InteractsWithFormatting;
use Sourcetoad\EnhancedResources\Formatting\InvalidFormatDefinitionException;

/**
 * @template TResource
 *
 * @property-read TResource $resource
 */
abstract class Resource extends JsonResource
{
    use InteractsWithFormatting;

    /**
     * @param TResource $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->initializeFormatting(static::class);
    }

    /**
     * @return array<array-key, mixed>|Arrayable<array-key, mixed>|JsonSerializable
     */
    public function toArray(Request $request): array|Arrayable|JsonSerializable
    {
        $currentFormat = $this->formatHandler->current();

        if (!$currentFormat) {
            throw new FormatNotSelectedException(static::class);
        }

        $result = $currentFormat->methodReflection->invoke($this, $request);

        if (!is_array($result) && !($result instanceof Arrayable) && !($result instanceof JsonSerializable)) {
            throw new InvalidFormatDefinitionException(
                static::class,
                $currentFormat->name,
                get_debug_type($result),
            );
        }

        return $result;
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
