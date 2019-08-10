<?php

namespace Jasonej\EnhancedResources\Tests;

use Illuminate\Http\Resources\Json\JsonResource;
use Jasonej\EnhancedResources\EnhancedResource;

class Resource extends JsonResource
{
    use EnhancedResource;
}