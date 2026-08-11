<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use LogicException;
use ReflectionMethod;
use Sourcetoad\EnhancedResources\Resource;
use Throwable;

class FormatNameCollisionException extends LogicException
{
    /**
     * @param class-string<Resource<mixed>> $resourceClass
     * @param array<string, ReflectionMethod[]> $collisions
     */
    public function __construct(
        public readonly string $resourceClass,
        public readonly array $collisions,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Resource "%s" defines multiple format methods with the same name. The following format names resolve to multiple methods: %s',
                $this->resourceClass,
                collect(array_keys($this->collisions))->join(', ', ', and '),
            ),
            $code,
            $previous,
        );
    }

    /**
     * @return array{collisions: array<string, string[]>, resourceClass: string}
     */
    public function context(): array
    {
        return [
            'collisions' => array_map(
                fn(array $methods) => array_map(
                    fn(ReflectionMethod $method) => sprintf(
                        '%s::%s()',
                        $this->resourceClass,
                        $method->getName()
                    ),
                    $methods,
                ),
                $this->collisions,
            ),
            'resourceClass' => $this->resourceClass,
        ];
    }
}
