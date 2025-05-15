<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    protected $fillable = ['name']; // Allow 'name' to be mass-assigned

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'ability_role');
    }
}
