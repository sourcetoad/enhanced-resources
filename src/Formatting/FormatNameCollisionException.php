<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use LogicException;
use Throwable;

class FormatNameCollisionException extends LogicException
{
    /**
     * @param class-string $resourceClass
     * @param array<string, list<FormatEntry>> $collisions
     */
    public function __construct(
        public readonly string $resourceClass,
        public readonly array $collisions,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                'More than one method on %s is defined with the format names: %s',
                $this->resourceClass,
                collect($this->collisions)->keys()->join(', ', ', and '),
            ),
            $code,
            $previous,
        );
    }

    /**
     * @return array{
     *     collisions: array<string, list<string>>,
     *     resourceClass: class-string,
     * }
     */
    public function context(): array
    {
        return [
            'collisions' => array_map(
                fn(array $formatEntries): array => array_map(
                    fn(FormatEntry $formatEntry): string => (string) $formatEntry->methodReflection,
                    $formatEntries,
                ),
                $this->collisions,
            ),
            'resourceClass' => $this->resourceClass,
        ];
    }
}
