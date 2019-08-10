<?php

namespace Jasonej\EnhancedResources;

trait EnhancedCollection
{
    use EnhancedResource;

    public function toArray($request)
    {
        $this->collection = $this->collection
            ->map->append($this->appends)
            ->map->exclude($this->excludes)
            ->map->only($this->only);

        return parent::toArray($request);
    }
}