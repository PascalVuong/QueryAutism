<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'credit_account_id',
    'reservation_id',
    'type',
    'amount',
    'balance_after',
    'description',
    'occurred_at',
    'metadata',
])]
class CreditTransaction extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(CreditAccount::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_after' => 'decimal:2',
            'occurred_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
