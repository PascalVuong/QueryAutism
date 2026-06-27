<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\StockLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<StockLocation>
 */
class StockLocationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'venue_id' => null,
            'code' => strtoupper(fake()->unique()->bothify('LOC-####')),
            'name' => fake()->company().' Stock',
            'type' => 'retail',
            'status' => 'active',
            'settings' => null,
        ];
    }

    public function warehouse(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'warehouse',
        ]);
    }

    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'service',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
