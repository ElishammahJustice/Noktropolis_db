<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_approved',
        'status',
        'store_name',
        'store_description',
        'phone',
        'store_logo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_approved'       => 'boolean',
        'password'          => 'hashed',
    ];

    /**
     * The role that this user belongs to.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if the user has the given ability via their role.
     */
    public function hasAbility(string $ability): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->abilities()
                    ->where('name', $ability)
                    ->exists();
    }

    /**
     * Shortcut to check role name.
     */
    public function hasRole(string $role): bool
    {
        return optional($this->role)->slug === $role;
    }
}
