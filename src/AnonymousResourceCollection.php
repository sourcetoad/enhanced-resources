<?php

namespace Jasonej\EnhancedResources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection as IlluminateAnonymousResourceCollection;

class AnonymousResourceCollection extends IlluminateAnonymousResourceCollection
{
    use EnhancedCollection;
}
