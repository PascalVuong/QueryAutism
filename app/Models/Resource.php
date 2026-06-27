<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'facility_id',
    'code',
    'name',
    'type',
    'status',
    'capacity',
    'is_bookable',
    'settings',
])]
class Resource extends Model
{
    use HasFactory, SoftDeletes;

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function availabilityBlocks(): HasMany
    {
        return $this->hasMany(ResourceAvailabilityBlock::class);
    }

    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(
            Reservation::class,
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
            'is_bookable' => 'boolean',
            'settings' => 'array',
        ];
    }
}
