<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Exceptions;

use Exception;

class FormatNameCollisionException extends Exception
{
    protected const MESSAGE_FORMAT = 'The \'%s\' format was defined multiple times for \'%s\'.';

    public function __construct(object $object, string $formatName)
    {
        $message = vsprintf(static::MESSAGE_FORMAT, [
            $formatName,
            $object::class,
        ]);

        parent::__construct($message);
    }
}