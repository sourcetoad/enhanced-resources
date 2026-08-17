<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use LogicException;
use Throwable;

class UnregisteredClassException extends LogicException
{
    /**
     * @param class-string $unregisteredClass
     */
    public function __construct(
        public readonly string $unregisteredClass,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                '%s must be registered with the %s before it can be resolved.',
                $this->unregisteredClass,
                FormatRegistrar::class,
            ),
            $code,
            $previous,
        );
    }

    /**
     * @return array{
     *     unregisteredClass: class-string,
     * }
     */
    public function context(): array
    {
        return [
            'unregisteredClass' => $this->unregisteredClass,
        ];
    }
}
