<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Sourcetoad\EnhancedResources\Contracts\EnhancementManager;

abstract class EnhancedCollection extends ResourceCollection
{
    protected EnhancementManager $manager;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->manager = resolve(EnhancementManager::class);
    }

    /** @return static */
    public function format(?string $format)
    {
        if (is_a($this->collects, EnhancedResource::class, true)) {
            $this->collection->map(
                fn(EnhancedResource $resource) => $resource->format($format),
            );
        }

        return $this;
    }
}
