<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Sourcetoad\EnhancedResources\Exceptions\NoDefinedFormatsException;
use Sourcetoad\EnhancedResources\Exceptions\NoFormatSelectedException;
use Sourcetoad\EnhancedResources\Formatting\FormatManager;
use Sourcetoad\EnhancedResources\Traits\SetsResponseStatus;

abstract class Resource extends JsonResource
{
    use SetsResponseStatus;

    protected FormatManager $formatManager;

    protected Collection $modifications;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->formatManager = new FormatManager($this);
        $this->modifications = new Collection;

        if ($this->formatManager->formats()->isEmpty()) {
            throw new NoDefinedFormatsException($this);
        }
    }

    public static function collection($resource): AnonymousResourceCollection
    {
        return tap(resolve(AnonymousResourceCollection::class, ['resource' => $resource, 'collects' => static::class]), function ($collection) {
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

    public function modify(callable|array $modification): static
    {
        $modification = ! is_callable($modification)
            ? fn (array $data) => array_merge($data, $modification)
            : $modification;

        $this->modifications->push($modification);

        return $this;
    }

    public function toArray($request)
    {
        $currentFormat = $this->formatManager->current() ?? throw new NoFormatSelectedException($this);
        $data = $currentFormat->invoke($this, $request);

        return $this->modifications->reduce(fn ($carry, $modification) => $modification($carry, $this), $data);
    }
}
