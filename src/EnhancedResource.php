<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Sourcetoad\EnhancedResources\Exceptions\UndefinedFormatException;

abstract class EnhancedResource extends JsonResource
{
    protected ?string $format;

    public function __construct($resource, ?string $format = null)
    {
        parent::__construct($resource);

        $this->format($format);
    }

    /** @return static */
    public function format(?string $format)
    {
        $this->format = $format;

        $formatMethodName = $this->getFormatMethodName();

        if (
            $formatMethodName
            && !method_exists($this, $formatMethodName)
        ) {
            $this->format = null;

            throw new UndefinedFormatException(static::class, $format);
        }

        return $this;
    }

    public function toArray($request): array
    {
        $method = $this->getFormatMethodName();

        if ($method === null) {
            return parent::toArray($request);
        }

        return $this->$method($request);
    }

    protected function getFormatMethodName(): ?string
    {
        if ($this->format === null) {
            return null;
        }

        return Str::camel($this->format . 'Format');
    }
}
