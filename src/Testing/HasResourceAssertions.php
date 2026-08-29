<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Testing;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;

trait HasResourceAssertions
{
    public function assertResourceContent(JsonResource $resource, Closure $callback): static
    {
        $response = TestResponse::fromBaseResponse($resource->response());

        $response->assertJson(fn(AssertableJson $json) => $resource::$wrap !== null
            ? $json->has($resource::$wrap, $callback)
            : $callback($json)
        );

        return $this;
    }

    /**
     * @param TestResponse<JsonResponse> $response
     */
    public function assertResourceResponse(JsonResource $resource, TestResponse $response): static
    {
        $response->assertJson($this->resourceJsonAssertion($resource, true));

        return $this;
    }

    public function resourceJsonAssertion(JsonResource $resource, bool $wrapped = false): Closure
    {
        return function (AssertableJson $json) use ($resource, $wrapped) {
            $content = $wrapped ? $resource->response()->content() : $resource->toJson();
            /** @var array<array-key, mixed> $expected */
            $expected = json_decode($content, true);

            $json->whereAll($expected);
        };
    }
}
