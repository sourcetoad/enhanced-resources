<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use LogicException;
use Throwable;

class NoDefinedFormatsException extends LogicException
{
    /**
     * @param class-string $resourceClass
     */
    public function __construct(
        public readonly string $resourceClass,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                '%s must define at least one format.',
                $this->resourceClass,
            ),
            $code,
            $previous,
        );
    }

    /**
     * @return array{
     *     resourceClass: class-string,
     * }
     */
    public function context(): array
    {
        return [
            'resourceClass' => $this->resourceClass,
        ];
    }
}
