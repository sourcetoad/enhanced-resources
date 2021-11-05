<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Exceptions;

use InvalidArgumentException;

class InvalidFormatException extends InvalidArgumentException
{
    protected const MESSAGE_FORMAT = 'Could not select the \'%s\' format for \'%s\' as it is not defined.';

    public function __construct(object $object, string $formatName)
    {
        $message = vsprintf(static::MESSAGE_FORMAT, [
            $formatName,
            $object::class,
        ]);

        parent::__construct($message);
    }
}
