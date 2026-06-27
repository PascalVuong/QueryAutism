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
    'product_category_id',
    'code',
    'name',
    'status',
    'product_type',
    'description',
    'tax_rate',
    'is_stock_tracked',
])]
class Product extends Model
{
    use HasFactory, SoftDeletes;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ProductCategory::class,
            'product_category_id',
        );
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    protected function casts(): array
    {
        return [
            'tax_rate' => 'decimal:2',
            'is_stock_tracked' => 'boolean',
        ];
    }
}
