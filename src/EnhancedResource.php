<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

abstract class EnhancedResource extends JsonResource
{
    protected $format = '';

    public function __construct($resource, string $format = '')
    {
        parent::__construct($resource);

        $this->format = $format;
    }

    public function format($request): array
    {
        return parent::toArray($request);
    }

    public function toArray($request)
    {
        $method = Str::camel($this->format.'Format');

        return $this->$method($request);
    }
}