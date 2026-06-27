<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'payment_id',
    'reservation_id',
    'requested_by_user_id',
    'status',
    'amount',
    'currency',
    'reason',
    'provider_reference',
    'processed_at',
    'metadata',
])]
class Refund extends Model
{
    use HasFactory, SoftDeletes;

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function requestedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'processed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
