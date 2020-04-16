<?php

namespace Sourcetoad\EnhancedResources\Tests;

use Sourcetoad\EnhancedResources\EnhancedResource;

class UserResource extends EnhancedResource
{
    public function maskEmailAddress(string $value): string
    {
        [$local, $domain] = explode('@', $value);

        $firstCharacter = substr($local, 0, 1);

        return "{$firstCharacter}*****@{$domain}";
    }

    public function maskPassword(): string
    {
        return '*****';
    }
}
