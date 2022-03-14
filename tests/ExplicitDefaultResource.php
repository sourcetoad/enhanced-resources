<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests;

use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Resource;

class ExplicitDefaultResource extends Resource
{
    #[Format]
    public function bar(): array
    {
        return [
            'id' => $this->resource->id,
            'name' => [
                'first' => $this->resource->firstName,
                'last' => $this->resource->lastName,
            ],
        ];
    }

    #[IsDefault, Format]
    public function foo(): array
    {
        return [
            'first_name' => $this->resource->firstName,
            'id' => $this->resource->id,
            'last_name' => $this->resource->lastName,
        ];
    }
}
