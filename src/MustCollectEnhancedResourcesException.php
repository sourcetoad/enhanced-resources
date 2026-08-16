<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use LogicException;
use Throwable;

class MustCollectEnhancedResourcesException extends LogicException
{
    /**
     * @param class-string $resourceCollectionClass
     * @param class-string|'null' $actuallyCollects
     */
    public function __construct(
        public readonly string $resourceCollectionClass,
        public readonly string $actuallyCollects,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                '%s must collect subclasses of %s, but actually collects %s.',
                $this->resourceCollectionClass,
                Resource::class,
                $this->actuallyCollects,
            ),
            $code,
            $previous,
        );
    }

    /**
     * @return array{
     *     actuallyCollects: class-string|'null',
     *     resourceCollectionClass: class-string,
     * }
     */
    public function context(): array
    {
        return [
            'actuallyCollects' => $this->actuallyCollects,
            'resourceCollectionClass' => $this->resourceCollectionClass,
        ];
    }
}
