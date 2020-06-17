<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests;

use Sourcetoad\EnhancedResources\EnhancedResource;

class UserResource extends EnhancedResource
{
    public function alternativeFormat($request): array
    {
        return [
            'email_address' => $this->resource->email_address,
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name
        ];
    }
}
