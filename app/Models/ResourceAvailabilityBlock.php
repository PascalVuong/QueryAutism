<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'resource_id',
    'type',
    'starts_at',
    'ends_at',
    'reason',
    'created_by_user_id',
    'metadata',
])]
class ResourceAvailabilityBlock extends Model
{
    use HasFactory, SoftDeletes;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
