<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use LogicException;
use Throwable;

class InvalidCollectsException extends LogicException
{
    /**
     * @param class-string $collectionClass
     * @param class-string|null $collects
     */
    public function __construct(
        public readonly string $collectionClass,
        public readonly ?string $collects,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            message: sprintf(
                '%s must collect subclasses of %s, %s given.',
                $this->collectionClass,
                Resource::class,
                $this->collects ?? 'null',
            ),
            code: $code,
            previous: $previous,
        );
    }

    /**
     * @return array{collectionClass: class-string, collects: class-string|null}
     */
    public function context(): array
    {
        return [
            'collectionClass' => $this->collectionClass,
            'collects' => $this->collects,
        ];
    }
}
