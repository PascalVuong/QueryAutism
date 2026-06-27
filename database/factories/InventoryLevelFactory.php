<?php

namespace Database\Factories;

use App\Models\InventoryLevel;
use App\Models\ProductVariant;
use App\Models\StockLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryLevel>
 */
class InventoryLevelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'stock_location_id' => StockLocation::factory(),
            'quantity_on_hand' => 20,
            'quantity_reserved' => 0,
            'reorder_point' => 5,
        ];
    }

    public function forVariantAtLocation(
        ProductVariant $variant,
        StockLocation $location,
    ): static {
        return $this->state(fn (array $attributes) => [
            'product_variant_id' => $variant->id,
            'stock_location_id' => $location->id,
        ]);
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity_on_hand' => 3,
            'quantity_reserved' => 1,
            'reorder_point' => 5,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity_on_hand' => 0,
            'quantity_reserved' => 0,
            'reorder_point' => 3,
        ]);
    }
}
