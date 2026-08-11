<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Sourcetoad\EnhancedResources\Resource;

trait InteractsWithFormatting
{
    protected FormatHandler $formatHandler;

    /**
     * @param class-string<Resource<mixed>> $resourceClass
     */
    public function initializeFormatting(string $resourceClass): void
    {
        $this->formatHandler = resolve(FormatHandler::class, [
            'resourceClass' => $resourceClass,
        ]);

        $this->formatHandler->registry()->register($resourceClass);
    }

    public function format(string $name): static
    {
        $this->formatHandler->select($name);

        return $this;
    }
}
