<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;
use ReflectionClass;
use Sourcetoad\EnhancedResources\Exceptions\CannotEnhanceBaseResourcesException;
use Sourcetoad\EnhancedResources\Traits\SetsResponseStatus;

/**
 * @method $this modify(callable|array $modification)
 */
abstract class ResourceCollection extends BaseResourceCollection
{
    use SetsResponseStatus;

    public function __construct($resource)
    {
        parent::__construct($resource);

        if (! is_a($this->collects, Resource::class, true)) {
            throw new CannotEnhanceBaseResourcesException($this->collects);
        }
    }

    public function __call($method, $parameters): mixed
    {
        if ((new ReflectionClass($this->collects))->hasMethod($method)) {
            $this->collection->map(fn (Resource $resource) => $resource->{$method}(...$parameters));

            return $this;
        }

        return parent::__call($method, $parameters);
    }

    public function format(string $name): static
    {
        $this->collection->each(fn (Resource $resource) => $resource->format($name));

        return $this;
    }
}
