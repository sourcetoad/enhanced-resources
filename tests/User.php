<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests;

use Illuminate\Database\Eloquent\Model;
use Sourcetoad\EnhancedResources\Support\Concerns\Resourceable;
use Sourcetoad\EnhancedResources\Support\Contracts\Resourceable as ResourceableContract;

final class User extends Model implements ResourceableContract
{
    use Resourceable;

    public static $resourceClass = null;

    protected $guarded = [];

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
