<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'membership_id',
    'from_status',
    'to_status',
    'reason',
    'changed_by_user_id',
    'effective_at',
    'metadata',
])]
class MembershipStatusHistory extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'effective_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
