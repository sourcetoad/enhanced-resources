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
use Sourcetoad\EnhancedResources\Formatting\HasFormats;
use Sourcetoad\EnhancedResources\Formatting\InvalidFormatResultException;

/**
 * @template TContent
 * @phpstan-type TCollectionSource iterable<array-key, TContent>|AbstractCursorPaginator<array-key, TContent>|AbstractPaginator<array-key, TContent>
 */
abstract class Resource extends JsonResource
{
    use HasFormats;

    /**
     * @param TContent $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->initializeFormatHandling(static::class);
    }

    /**
     * @return array<array-key, mixed>|Arrayable<array-key, mixed>|JsonSerializable
     */
    #[Override]
    public function toArray(Request $request): array|Arrayable|JsonSerializable
    {
        $selectedFormat = $this->formatHandler->selected();

        if ($selectedFormat === null) {
            throw new FormatNotSelectedException;
        }

        $result = $selectedFormat->methodReflection->invoke($this, $request);

        if (!($result instanceof Arrayable) && !($result instanceof JsonSerializable) && !is_array($result)) {
            throw new InvalidFormatResultException(
                static::class,
                $selectedFormat,
                get_debug_type($result),
            );
        }

        return $result;
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
