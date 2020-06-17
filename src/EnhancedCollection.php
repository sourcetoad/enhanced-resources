<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Sourcetoad\EnhancedResources\Contracts\EnhancementManager;

/**
 * @method $this append(string ...$keys)
 * @method $this call(callable $callable, ...$params)
 * @method $this exclude(string ...$keys)
 * @method $this only(string ...$keys)
 * @method $this replace(array $data, bool $recursive = false)
 */
abstract class EnhancedCollection extends ResourceCollection
{
    protected EnhancementManager $manager;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->manager = resolve(EnhancementManager::class);
    }

    public function __call($method, $parameters)
    {
        if (
            is_a($this->collects, EnhancedResource::class, true)
            && $this->manager->hasEnhancement($method, $this->collects)
        ) {
            $this->collection->map(
                fn(EnhancedResource $resource) => $resource->$method(...$parameters)
            );

            return $this;
        }

        return parent::__call($method, $parameters);
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
