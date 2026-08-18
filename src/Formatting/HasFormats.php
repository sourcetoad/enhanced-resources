<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Sourcetoad\EnhancedResources\Resource;

trait HasFormats
{
    private FormatHandler $formatHandler;

    /**
     * @param class-string<Resource<mixed>> $resourceClass
     */
    public function initializeFormatHandling(string $resourceClass): void
    {
        $this->formatHandler = resolve(FormatHandler::class, [
            'resourceClass' => $resourceClass,
        ]);
    }

    public function format(string $name): static
    {
        $this->formatHandler->select($name);

        return $this;
    }
}
