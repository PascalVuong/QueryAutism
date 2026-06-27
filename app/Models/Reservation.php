<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'organization_id',
    'venue_id',
    'customer_id',
    'created_by_user_id',
    'reference_number',
    'status',
    'starts_at',
    'ends_at',
    'party_size',
    'subtotal',
    'discount_total',
    'total',
    'currency',
    'notes',
    'cancelled_at',
    'cancellation_reason',
])]
class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(ReservationParticipant::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ReservationStatusHistory::class);
    }

    public function latestStatusHistory(): HasOne
    {
        return $this->hasOne(ReservationStatusHistory::class)
            ->latestOfMany();
    }

    public function effectiveStatusHistory(): HasOne
    {
        return $this->hasOne(ReservationStatusHistory::class)
            ->ofMany([
                'effective_at' => 'max',
                'id' => 'max',
            ]);
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(
            Resource::class,
            'reservation_items',
        )
            ->withPivot([
                'id',
                'starts_at',
                'ends_at',
                'quantity',
                'unit_price',
                'total_price',
                'status',
            ])
            ->withTimestamps();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'total' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }
}
