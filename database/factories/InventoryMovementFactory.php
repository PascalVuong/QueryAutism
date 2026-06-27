<?php

namespace Database\Factories;

use App\Models\InventoryMovement;
use App\Models\ProductVariant;
use App\Models\StockLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryMovement>
 */
class InventoryMovementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'stock_location_id' => StockLocation::factory(),
            'sales_order_id' => null,
            'sales_order_item_id' => null,
            'type' => 'adjustment',
            'quantity' => 1,
            'quantity_after' => 1,
            'reason' => fake()->optional()->sentence(),
            'occurred_at' => now(),
            'metadata' => null,
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

    public function sale(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'sale',
            'quantity' => -1,
        ]);
    }

    public function purchase(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'purchase',
            'quantity' => 1,
        ]);
    }
}
