<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'reservation_id',
    'resource_id',
    'starts_at',
    'ends_at',
    'quantity',
    'unit_price',
    'total_price',
    'status',
    'notes',
])]
class ReservationItem extends Model
{
    use HasFactory, SoftDeletes;

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(ReservationCharge::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }
}
