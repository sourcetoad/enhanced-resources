<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Exceptions;

use Exception;

class UndefinedFormatException extends Exception
{
    public function __construct(string $resourceClass, string $format)
    {
        parent::__construct("No '{$format}' format was defined for {$resourceClass}");
    }
}
