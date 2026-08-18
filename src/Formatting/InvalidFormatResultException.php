<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use LogicException;
use Throwable;

class InvalidFormatResultException extends LogicException
{
    /**
     * @param class-string $resourceClass
     */
    public function __construct(
        public readonly string $resourceClass,
        public readonly FormatEntry $formatEntry,
        public readonly string $resultType,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                'The %s format of %s returned an invalid type: %s',
                $this->formatEntry->name,
                $this->resourceClass,
                $this->resultType,
            ),
            $code,
            $previous,
        );
    }

    /**
     * @return array{
     *     formatMethod: string,
     *     formatName: string,
     *     resourceClass: class-string,
     *     resultType: string,
     * }
     */
    public function context(): array
    {
        return [
            'formatMethod' => (string) $this->formatEntry->methodReflection,
            'formatName' => $this->formatEntry->name,
            'resourceClass' => $this->resourceClass,
            'resultType' => $this->resultType,
        ];
    }
}
