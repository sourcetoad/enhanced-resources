<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Resources;

use Illuminate\Http\Request;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Resource;

/**
 * @extends Resource<BasicContent>
 * @property-read BasicContent $resource
 */
class MultiFormatResource extends Resource
{
    public const string ALT = 'alt';
    public const string ARRAYABLE = 'arrayable';
    public const string BASE = 'base';
    public const string JSON_SERIALIZABLE = 'json_serializable';

    #[Format(self::ARRAYABLE)]
    public function arrayable(): ArrayableResult
    {
        return new ArrayableResult($this->resource);
    }

    #[Format(self::BASE)]
    public function baseFormat(): array
    {
        return [
            'content' => $this->resource->content,
        ];
    }

    #[Format(self::JSON_SERIALIZABLE)]
    public function jsonSerializable(): JsonSerializableResult
    {
        return new JsonSerializableResult($this->resource);
    }

    #[Format(self::ALT)]
    public function withRequestFormat(Request $request): array
    {
        return [
            'content' => $this->resource->content,
            'query' => $request->query('q'),
        ];
    }
}
