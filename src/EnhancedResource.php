<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Sourcetoad\EnhancedResources\Contracts\EnhancementManager;
use Sourcetoad\EnhancedResources\Exceptions\UndefinedFormatException;

/**
 * @method $this append(string ...$keys)
 * @method $this call(callable $callable, ...$params)
 * @method $this exclude(string ...$keys)
 * @method $this only(string ...$keys)
 * @method $this replace(array $data, bool $recursive = false)
 */
class EnhancedResource extends JsonResource
{
    protected array $enhancements = [];
    protected ?string $format = null;
    protected EnhancementManager $manager;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->manager = resolve(EnhancementManager::class);
    }

    public function __call($method, $parameters)
    {
        if ($this->manager->hasEnhancement($method, static::class)) {
            $this->enhancements[] = [
                'name'       => $method,
                'parameters' => $parameters,
            ];

            return $this;
        }

        return parent::__call($method, $parameters);
    }

    public static function collection($resource): EnhancedAnonymousCollection
    {
        return tap(new EnhancedAnonymousCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
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

        $data = $method === null
            ? parent::toArray($request)
            : $this->$method($request);

        foreach ($this->enhancements as ['name' => $name, 'parameters' => $parameters]) {
            $enhancement = $this->manager->getEnhancement($name, static::class);

            $data = call_user_func(
                $enhancement,
                $this,
                $data,
                ...$parameters,
            );
        }

        return $data;
    }

    protected function getFormatMethodName(): ?string
    {
        if ($this->format === null) {
            return null;
        }

        return Str::camel($this->format . 'Format');
    }
}
