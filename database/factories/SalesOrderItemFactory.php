<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesOrderItem>
 */
class SalesOrderItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 4);
        $unitPrice = fake()->randomFloat(2, 5, 100);

        return [
            'sales_order_id' => SalesOrder::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount_total' => 0,
            'tax_total' => 0,
            'line_total' => $quantity * $unitPrice,
            'status' => 'ordered',
        ];
    }

    public function forOrderAndVariant(
        SalesOrder $order,
        ProductVariant $variant,
    ): static {
        return $this->state(fn (array $attributes) => [
            'sales_order_id' => $order->id,
            'product_variant_id' => $variant->id,
        ]);
    }

    public function fulfilled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'fulfilled',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refunded',
        ]);
    }
}
