<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Resources;

use Illuminate\Http\Resources\Attributes\Collects;
use Illuminate\Http\Resources\Json\JsonResource;
use Sourcetoad\EnhancedResources\ResourceCollection;

#[Collects(JsonResource::class)]
class JsonResourceCollection extends ResourceCollection
{
    //
}
