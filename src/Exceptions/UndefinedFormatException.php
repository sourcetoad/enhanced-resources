<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Exceptions;

use InvalidArgumentException;

class UndefinedFormatException extends InvalidArgumentException
{
    public function __construct(string $resourceClass, string $format)
    {
        parent::__construct("No '{$format}' format was defined for {$resourceClass}");
    }
}
