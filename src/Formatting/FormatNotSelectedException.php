<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Formatting;

use LogicException;
use Throwable;

class FormatNotSelectedException extends LogicException
{
    public function __construct(
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            'No format is selected.',
            $code,
            $previous,
        );
    }
}
