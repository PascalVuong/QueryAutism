<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'organization_id',
    'customer_id',
    'membership_plan_id',
    'membership_number',
    'status',
    'starts_at',
    'ends_at',
    'activated_at',
    'cancelled_at',
    'cancellation_reason',
    'auto_renew',
    'agreed_price',
    'currency',
])]
class Membership extends Model
{
    use HasFactory, SoftDeletes;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function membershipPlan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(MembershipStatusHistory::class);
    }

    public function latestStatusHistory(): HasOne
    {
        return $this->hasOne(MembershipStatusHistory::class)
            ->latestOfMany();
    }

    public function effectiveStatusHistory(): HasOne
    {
        return $this->hasOne(MembershipStatusHistory::class)
            ->ofMany([
                'effective_at' => 'max',
                'id' => 'max',
            ]);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'activated_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'auto_renew' => 'boolean',
            'agreed_price' => 'decimal:2',
        ];
    }
}
