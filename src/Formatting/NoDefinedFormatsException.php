<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use LogicException;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Throwable;

class NoDefinedFormatsException extends LogicException
{
    public function __construct(
        public readonly string $resourceClass,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Resource "%s" does not define any format methods. Format methods must be defined with the "%s" attribute.',
                $this->resourceClass,
                Format::class,
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
