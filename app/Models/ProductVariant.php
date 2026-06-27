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
    'product_id',
    'sku',
    'name',
    'barcode',
    'status',
    'price',
    'cost_price',
    'attributes',
])]
class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryLevels(): HasMany
    {
        return $this->hasMany(InventoryLevel::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function salesOrderItems(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'attributes' => 'array',
        ];
    }
}
