<?php

namespace Sourcetoad\EnhancedResources\Tests;

use Sourcetoad\EnhancedResources\EnhancedResource;

/**
 * @property User $resource
 */
class UserResource extends EnhancedResource
{
    public function alternativeFormat($request)
    {
        return [
            'email_address' => $this->resource->email_address,
            'name' => $this->resource->name
        ];
    }
}