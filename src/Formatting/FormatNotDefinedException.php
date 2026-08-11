<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use LogicException;
use Throwable;

class FormatNotDefinedException extends LogicException
{
    public function __construct(
        public readonly string $resourceClass,
        public readonly string $formatName,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Resource "%s" does not define a format named "%s".',
                $this->resourceClass,
                $this->formatName,
            ),
            $code,
            $previous,
        );
    }

    /**
     * @return array{formatName: string, resourceClass: string}
     */
    public function context(): array
    {
        return [
            'formatName' => $this->formatName,
            'resourceClass' => $this->resourceClass,
        ];
    }
}
