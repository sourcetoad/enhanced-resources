<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Support\Concerns;

use Illuminate\Http\JsonResponse;
use Sourcetoad\EnhancedResources\EnhancedResource;

trait Resourceable
{
    public function toResource(?string $resourceClass = null): EnhancedResource
    {
        $resourceClass ??= static::$resourceClass ?? EnhancedResource::class;

        return $resourceClass::make($this);
    }

    public function toResponse(
        ?string $resourceClass = null,
        ?string $format = null,
        ?int $statusCode = null
    ): JsonResponse {
        $resource = $this->toResource($resourceClass);

        if ($format !== null) {
            $resource = $resource->format($format);
        }

        $response = $resource->response();

        if ($statusCode !== null) {
            $response = $response->setStatusCode($statusCode);
        }

        return $response;
    }
}
