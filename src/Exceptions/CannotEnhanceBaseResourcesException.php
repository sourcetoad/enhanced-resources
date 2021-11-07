<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Exceptions;

use Exception;

class CannotEnhanceBaseResourcesException extends Exception
{
    protected const MESSAGE_FORMAT = 'Cannot enhance collections of \'%s\' because it is not an enhanced resource.';

    public function __construct(string $resourceName)
    {
        $message = vsprintf(static::MESSAGE_FORMAT, [
            $resourceName,
        ]);

        parent::__construct($message);
    }
}
