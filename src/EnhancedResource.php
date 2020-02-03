<?php

namespace Sourcetoad\EnhancedResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Sourcetoad\EnhancedResources\Concerns\CustomHooks;
use Sourcetoad\EnhancedResources\Concerns\Enhanced;
use Sourcetoad\EnhancedResources\Concerns\ExcludesData;
use Sourcetoad\EnhancedResources\Concerns\IncludesData;
use Sourcetoad\EnhancedResources\Concerns\MasksData;

class EnhancedResource extends JsonResource
{
    use CustomHooks, Enhanced, ExcludesData, IncludesData, MasksData;

    protected static $anonymousResourceCollectionClass = EnhancedAnonymousResourceCollection::class;

    public function __construct($resource)
    {
        parent::__construct($resource);

        static::bootTraits();
    }

    public static function collection($resource): EnhancedAnonymousResourceCollection
    {
        return tap(
            new static::$anonymousResourceCollectionClass($resource, static::class),
            function ($collection) {
                if (property_exists(static::class, 'preserveKeys')) {
                    $collection->preserveKeys = (new static([]))->preserveKeys === true;
                }
            }
        );
    }
}