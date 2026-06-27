<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_variant_id',
    'stock_location_id',
    'quantity_on_hand',
    'quantity_reserved',
    'reorder_point',
])]
class InventoryLevel extends Model
{
    use HasFactory;

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function stockLocation(): BelongsTo
    {
        return $this->belongsTo(StockLocation::class);
    }
}
