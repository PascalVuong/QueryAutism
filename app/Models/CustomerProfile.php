<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'customer_id',
    'preferred_language',
    'preferred_timezone',
    'preferred_currency',
    'average_booking_value',
    'total_reservations',
    'total_spent',
    'no_show_count',
    'cancellation_count',
    'loyalty_tier',
    'risk_score',
    'preferences',
    'last_recalculated_at',
])]
class CustomerProfile extends Model
{
    use HasFactory;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'average_booking_value' => 'decimal:2',
            'total_spent' => 'decimal:2',
            'risk_score' => 'decimal:2',
            'preferences' => 'array',
            'last_recalculated_at' => 'datetime',
        ];
    }
}
