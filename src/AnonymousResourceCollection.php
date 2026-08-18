<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as IlluminateAnonymousResourceCollection;
use JsonSerializable;
use Override;
use Sourcetoad\EnhancedResources\Formatting\FormatNotSelectedException;
use Sourcetoad\EnhancedResources\Formatting\HasFormats;
use Traversable;

/**
 * @template TContent
 * @phpstan-import-type TCollectionSource from Resource as TSource
 */
class AnonymousResourceCollection extends IlluminateAnonymousResourceCollection
{
    use HasFormats;

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

        $this->initializeFormatHandling($collects);

        parent::__construct($resource, $collects);
    }

    /**
     * @return Traversable<array-key, TContent>
     */
    public function getIterator(): Traversable
    {
        return parent::getIterator();
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

        $this->collection?->each(function (mixed $resource) use ($selectedFormat) {
            if ($resource instanceof Resource) {
                $resource->format($selectedFormat->name);
            }
        });

        return parent::toArray($request);
    }
}
