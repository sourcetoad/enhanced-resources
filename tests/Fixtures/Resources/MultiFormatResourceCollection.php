<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests\Fixtures\Resources;

use Illuminate\Http\Resources\Attributes\Collects;
use Sourcetoad\EnhancedResources\ResourceCollection;

#[Collects(MultiFormatResource::class)]
class MultiFormatResourceCollection extends ResourceCollection
{
    //
}
