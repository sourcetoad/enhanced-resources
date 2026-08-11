<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use LogicException;
use Throwable;

class FormatNotSelectedException extends LogicException
{
    public function __construct(
        public readonly string $resourceClass,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'No format was selected for resource "%s".',
                $this->resourceClass,
            ),
            $code,
            $previous,
        );
    }

    /**
     * @return array{resourceClass: string}
     */
    public function context(): array
    {
        return [
            'resourceClass' => $this->resourceClass,
        ];
    }
}
