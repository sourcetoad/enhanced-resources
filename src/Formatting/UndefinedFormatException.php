<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use LogicException;
use Throwable;

class UndefinedFormatException extends LogicException
{
    /**
     * @param class-string $resourceClass
     */
    public function __construct(
        public readonly string $resourceClass,
        public readonly string $format,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                '%s does not have a defined format with the name "%s".',
                $this->resourceClass,
                $this->format,
            ),
            $code,
            $previous,
        );
    }

    /**
     * @return array{
     *     format: string,
     *     resourceClass: class-string,
     * }
     */
    public function context(): array
    {
        return [
            'format' => $this->format,
            'resourceClass' => $this->resourceClass,
        ];
    }
}
