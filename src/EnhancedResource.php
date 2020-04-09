<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

abstract class EnhancedResource extends JsonResource
{
    protected static $enhancements = [];
    protected $format = '';

    public function __construct($resource, string $format = '')
    {
        parent::__construct($resource);

        $this->format = $format;
    }

    public static function enhance(string $name, $enhancement)
    {
        if (!is_subclass_of($enhancement, Enhancement::class)) {
            throw new InvalidArgumentException('Invalid enhancement.');
        }

        static::$enhancements[static::class][$name] = $enhancement;
    }

    public function format($request): array
    {
        return parent::toArray($request);
    }

    public static function hasEnhancement(string $name): bool
    {
        return Arr::has(static::$enhancements, static::class.'.'.$name);
    }

    public function toArray($request)
    {
        $method = Str::camel($this->format.'Format');

        return $this->$method($request);
    }
}