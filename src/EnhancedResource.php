<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Sourcetoad\EnhancedResources\Enhancements\Enhancement;

abstract class EnhancedResource extends JsonResource
{
    protected $appliedEnhancements = [];
    protected static $enhancements = [];
    protected $format = '';

    public function __construct($resource, string $format = '')
    {
        parent::__construct($resource);

        $this->format = $format;
    }

    public static function enhance(string $name, $enhancement)
    {
        if (
            !is_callable($enhancement)
            && !is_subclass_of($enhancement, Enhancement::class)
        ) {
            throw new InvalidArgumentException('Invalid enhancement.');
        }

        static::$enhancements[static::class][$name] = $enhancement;
    }

    public function format($request): array
    {
        return parent::toArray($request);
    }

    public static function getEnhancement(string $name)
    {
        $enhancement = Arr::get(static::$enhancements, static::class.'.'.$name);

        if ($enhancement !== null) {
            return $enhancement;
        }

        foreach (class_parents(static::class) as $ancestor) {
            $enhancement = Arr::get(static::$enhancements, "{$ancestor}.{$name}");

            if ($enhancement !== null) {
                return $enhancement;
            }
        }

        return null;
    }

    public static function hasEnhancement(string $name): bool
    {
        return static::getEnhancement($name) !== null;
    }

    public function toArray($request)
    {
        $method = Str::camel($this->format.'Format');

        return $this->$method($request);
    }

    public function __call($method, $parameters)
    {
        $enhancement = static::getEnhancement($method);

        if (is_callable($enhancement)) {
            $this->appliedEnhancements[] = [
                'enhancement' => $enhancement,
                'parameters' => $parameters
            ];

            return $this;
        }

        if (is_subclass_of($enhancement, Enhancement::class)) {
            $this->appliedEnhancements[] = new $enhancement(...$parameters);

            return $this;
        }

        return parent::__call($method, $parameters);
    }
}