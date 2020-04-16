<?php

namespace Sourcetoad\EnhancedResources\Tests;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string email_address
 * @property string first_name
 * @property int id
 * @property string last_name
 * @property string password
 */
class User extends Model
{
    protected $guarded = [];

    public function getFirstInitialAttribute(): string
    {
        return substr($this->first_name, 0, 1);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getLastInitialAttribute(): string
    {
        return substr($this->last_name, 0, 1);
    }
}
