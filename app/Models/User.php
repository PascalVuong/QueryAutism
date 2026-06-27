<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'uuid',
    'name',
    'email',
    'email_verified_at',
    'password',
    'status',
    'locale',
    'timezone',
    'last_login_at',
    'last_login_ip',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(
            Organization::class,
            'organization_users',
        )
            ->using(OrganizationUser::class)
            ->withPivot([
                'id',
                'role',
                'status',
                'is_owner',
                'invited_by_user_id',
                'invited_at',
                'joined_at',
                'left_at',
            ])
            ->withTimestamps();
    }

    public function organizationMemberships(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function customerProfiles(): HasManyThrough
    {
        return $this->hasManyThrough(
            CustomerProfile::class,
            Customer::class,
        );
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }
}
