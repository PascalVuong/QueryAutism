<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 5, 150);

        return [
            'uuid' => Str::uuid(),
            'product_id' => Product::factory(),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-########')),
            'name' => fake()->words(2, true),
            'barcode' => null,
            'status' => 'active',
            'price' => $price,
            'cost_price' => round($price * 0.55, 2),
            'attributes' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }
}
