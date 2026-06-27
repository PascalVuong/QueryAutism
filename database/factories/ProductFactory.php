<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'product_category_id' => null,
            'code' => strtoupper(fake()->unique()->bothify('PROD-####')),
            'name' => fake()->words(3, true),
            'status' => 'draft',
            'product_type' => 'physical',
            'description' => fake()->optional()->sentence(),
            'tax_rate' => 21,
            'is_stock_tracked' => true,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }

    public function digital(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'digital',
            'is_stock_tracked' => false,
        ]);
    }

    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_type' => 'service',
            'is_stock_tracked' => false,
        ]);
    }
}
