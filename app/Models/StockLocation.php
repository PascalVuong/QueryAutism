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
    'code',
    'name',
    'type',
    'status',
    'settings',
])]
class StockLocation extends Model
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

    public function inventoryLevels(): HasMany
    {
        return $this->hasMany(InventoryLevel::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class);
    }

    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }
}
