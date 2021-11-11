<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Exceptions;

use Exception;

class NoResourceDefinedException extends Exception
{
    protected const MESSAGE_FORMAT = '\'%s\' could not be converted to a resource as no resource was defined.';

    public function __construct(object $object)
    {
        $message = vsprintf(static::MESSAGE_FORMAT, [
            $object::class,
        ]);

        parent::__construct($message);
    }
}
