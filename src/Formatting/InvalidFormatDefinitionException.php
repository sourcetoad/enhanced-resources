<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use LogicException;
use Throwable;

class InvalidFormatDefinitionException extends LogicException
{
    public function __construct(
        public readonly string $resourceClass,
        public readonly string $formatName,
        public readonly string $resultType,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Format "%s" of resource "%s" must return %s, received %s.',
                $this->formatName,
                $this->resourceClass,
                collect(['array', Arrayable::class, JsonSerializable::class])->join(', ', ', or'),
                $this->resultType,
            ),
            $code,
            $previous,
        );
    }

    /**
     * @return array{formatName: string, resourceClass: string, resultType: string}
     */
    public function context(): array
    {
        return [
            'formatName' => $this->formatName,
            'resourceClass' => $this->resourceClass,
            'resultType' => $this->resultType,
        ];
    }
}
