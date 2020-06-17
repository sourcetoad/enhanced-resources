<?php

declare(strict_types=1);

namespace Sourcetoad\EnhancedResources\Tests;

use Illuminate\Database\Eloquent\Model;

final class User extends Model
{
    protected $guarded = [];

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
