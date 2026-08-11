<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Sourcetoad\EnhancedResources\Resource;

class FormatHandler
{
    protected ?FormatEntry $current = null;

    /**
     * @param class-string<Resource<mixed>> $resourceClass
     */
    public function __construct(
        protected FormatRegistry $registry,
        protected string $resourceClass,
    ) {
        //
    }

    public function current(): ?FormatEntry
    {
        return $this->current;
    }

    public function registry(): FormatRegistry
    {
        return $this->registry;
    }

    public function select(string $name): void
    {
        $this->current = $this->registry()->resolve($this->resourceClass, $name)
            ?? throw new FormatNotDefinedException($this->resourceClass, $name);
    }
}
