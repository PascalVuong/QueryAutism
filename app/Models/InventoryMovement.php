<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_variant_id',
    'stock_location_id',
    'sales_order_id',
    'sales_order_item_id',
    'type',
    'quantity',
    'quantity_after',
    'reason',
    'occurred_at',
    'metadata',
])]
class InventoryMovement extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function stockLocation(): BelongsTo
    {
        return $this->belongsTo(StockLocation::class);
    }

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function salesOrderItem(): BelongsTo
    {
        return $this->belongsTo(SalesOrderItem::class);
    }

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
