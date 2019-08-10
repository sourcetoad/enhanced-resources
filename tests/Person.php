<?php

namespace Jasonej\EnhancedResources\Tests;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $guarded = [];

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}