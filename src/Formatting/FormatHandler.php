<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Sourcetoad\EnhancedResources\Resource;

class FormatHandler
{
    private ?FormatEntry $selected = null;

    /**
     * @param class-string<Resource<mixed>> $resourceClass
     */
    public function __construct(
        private readonly FormatRegistrar $formatRegistrar,
        private readonly string $resourceClass,
    ) {
        $this->formatRegistrar->register($this->resourceClass);
    }

    public function select(string $format): void
    {
        $this->selected = $this->formatRegistrar->resolve($this->resourceClass, $format)
            ?? throw new UndefinedFormatException($this->resourceClass, $format);
    }

    public function selected(): ?FormatEntry
    {
        return $this->selected;
    }
}
