<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Traits;

use Illuminate\Http\JsonResponse;

trait SetsResponseStatus
{
    protected ?int $responseStatus = null;

    public function setResponseStatus(?int $code): static
    {
        $this->responseStatus = $code;

        return $this;
    }

    public function toResponse($request): JsonResponse
    {
        $response = parent::toResponse($request);

        if ($this->responseStatus !== null) {
            $response->setStatusCode($this->responseStatus);
        }

        return $response;
    }
}
