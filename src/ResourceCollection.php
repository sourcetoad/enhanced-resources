<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;
use Sourcetoad\EnhancedResources\Exceptions\CannotEnhanceBaseResourcesException;

abstract class ResourceCollection extends BaseResourceCollection
{
    public function __construct($resource)
    {
        parent::__construct($resource);

        if (!is_a($this->collects, Resource::class, true)) {
            throw new CannotEnhanceBaseResourcesException($this->collects);
        }
    }

    public function format(string $name): static
    {
        $this->collection->each(fn(Resource $resource) => $resource->format($name));

        return $this;
    }
}
