<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Sourcetoad\EnhancedResources\Exceptions\NoDefinedFormatsException;
use Sourcetoad\EnhancedResources\Exceptions\NoFormatSelectedException;
use Sourcetoad\EnhancedResources\Formatting\FormatManager;

abstract class Resource extends JsonResource
{
    protected FormatManager $formatManager;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->formatManager = new FormatManager($this);

        if ($this->formatManager->formats()->isEmpty()) {
            throw new NoDefinedFormatsException($this);
        }
    }

    public static function collection($resource): AnonymousResourceCollection
    {
        return tap(new AnonymousResourceCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }

    public function format(string $name): static
    {
        $this->formatManager->select($name);

        return $this;
    }

    public function toArray($request)
    {
        $currentFormat = $this->formatManager->current() ?? throw new NoFormatSelectedException($this);

        return $currentFormat->invoke($this, $request);
    }
}
