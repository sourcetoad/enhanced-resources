<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as IlluminateResourceCollection;
use JsonSerializable;
use Override;
use Sourcetoad\EnhancedResources\Formatting\FormatNotSelectedException;
use Sourcetoad\EnhancedResources\Formatting\HasFormats;
use Traversable;

/**
 * @template TContent
 * @phpstan-import-type TCollectionSource from Resource as TSource
 */
abstract class ResourceCollection extends IlluminateResourceCollection
{
    use HasFormats;

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

        $this->initializeFormatHandling($collects);

        parent::__construct($resource);
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
