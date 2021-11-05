<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Exceptions;

use Exception;

class MultipleDefaultFormatsException extends Exception
{
    protected const MESSAGE_FORMAT = '\'%s\' has multiple explicit default formats.';

    public function __construct(object $object)
    {
        $message = vsprintf(static::MESSAGE_FORMAT, [
            $object::class,
        ]);

        parent::__construct($message);
    }
}
