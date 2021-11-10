<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Enhancements;

use Illuminate\Support\Arr;

class Only
{
    public function __construct(
        protected array $keys,
    ) {}

    public function __invoke(array $data): array
    {
        return Arr::only($data, $this->keys);
    }
}
