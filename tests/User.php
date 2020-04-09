<?php

namespace Sourcetoad\EnhancedResources\Tests;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email_address
 * @property string $first_name
 * @property string $last_name
 * @property string $name
 * @property string $password
 */
class User extends Model
{
    protected $fillable = [
        'email_address',
        'first_name',
        'last_name',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}