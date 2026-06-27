<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'parent_id',
    'name',
    'legal_name',
    'slug',
    'registration_number',
    'vat_number',
    'email',
    'phone',
    'status',
    'timezone',
    'currency',
    'country_code',
    'settings',
    'onboarded_at',
])]
class Organization extends Model
{
    use HasFactory, SoftDeletes;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
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

    public function userMemberships(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function membershipPlans(): HasMany
    {
        return $this->hasMany(MembershipPlan::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function externalIdentifiers(): HasMany
    {
        return $this->hasMany(ExternalIdentifier::class);
    }

    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'onboarded_at' => 'datetime',
        ];
    }
}
