<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Enhancements;

use Sourcetoad\EnhancedResources\EnhancedResource;

class Replace
{
    public function __invoke(
        EnhancedResource $resource,
        array $data,
        array $replacementData,
        bool $recursive = false
    ): array {
        return $recursive
            ? array_replace_recursive($data, $replacementData)
            : array_replace($data, $replacementData);
    }
}
