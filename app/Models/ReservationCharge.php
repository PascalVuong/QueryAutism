<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'reservation_id',
    'reservation_item_id',
    'price_rule_id',
    'type',
    'direction',
    'description',
    'quantity',
    'unit_amount',
    'total_amount',
    'currency',
    'metadata',
])]
class ReservationCharge extends Model
{
    use HasFactory, SoftDeletes;

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function reservationItem(): BelongsTo
    {
        return $this->belongsTo(ReservationItem::class);
    }

    public function priceRule(): BelongsTo
    {
        return $this->belongsTo(PriceRule::class);
    }

    protected function casts(): array
    {
        return [
            'unit_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'metadata' => 'array',
        ];
    }
}
