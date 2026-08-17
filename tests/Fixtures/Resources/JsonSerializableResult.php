<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Resources;

use JsonSerializable;

readonly class JsonSerializableResult implements JsonSerializable
{
    public function __construct(
        public BasicContent $content,
    ) {
        //
    }

    public function jsonSerialize(): array
    {
        return [
            'content' => $this->content->content,
            'type' => 'json_serializable',
        ];
    }
}
