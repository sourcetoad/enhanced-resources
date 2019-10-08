<?php

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Sourcetoad\EnhancedResources\Concerns\Enhanced;
use Sourcetoad\EnhancedResources\Concerns\ExcludesData;
use Sourcetoad\EnhancedResources\Concerns\IncludesData;
use Sourcetoad\EnhancedResources\Concerns\MasksData;

class EnhancedResource extends JsonResource
{
    use Enhanced, ExcludesData, IncludesData, MasksData;

    public function __construct($resource)
    {
        parent::__construct($resource);

        static::bootTraits();
    }

    public static function collection($resource): EnhancedAnonymousResourceCollection
    {
        return tap(
            new EnhancedAnonymousResourceCollection($resource, static::class),
            function ($collection) {
                if (property_exists(static::class, 'preserveKeys')) {
                    $collection->preserveKeys = (new static([]))->preserveKeys === true;
                }
            }
        );
    }
}