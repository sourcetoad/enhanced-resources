<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Support\Contracts;

use Illuminate\Http\JsonResponse;
use Sourcetoad\EnhancedResources\EnhancedResource;

interface Resourceable
{
    public function toResource(?string $resourceClass = null): EnhancedResource;
    public function toResponse(
        ?string $resourceClass = null,
        ?string $format = null,
        ?int $statusCode = null
    ): JsonResponse;
}
