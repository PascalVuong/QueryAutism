<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'organization_id',
    'venue_id',
    'resource_id',
    'code',
    'name',
    'type',
    'status',
    'amount',
    'percentage',
    'priority',
    'is_stackable',
    'starts_at',
    'ends_at',
    'conditions',
])]
class PriceRule extends Model
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

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function reservationCharges(): HasMany
    {
        return $this->hasMany(ReservationCharge::class);
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'percentage' => 'decimal:4',
            'is_stackable' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'conditions' => 'array',
        ];
    }
}
