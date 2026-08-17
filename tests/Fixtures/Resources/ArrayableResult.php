<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Resources;

use Illuminate\Contracts\Support\Arrayable;

readonly class ArrayableResult implements Arrayable
{
    public function __construct(
        public BasicContent $content,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content->content,
            'type' => 'arrayable',
        ];
    }
}
