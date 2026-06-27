<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\PriceRule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PriceRule>
 */
class PriceRuleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'venue_id' => null,
            'resource_id' => null,
            'code' => strtoupper(fake()->unique()->bothify('RULE-####')),
            'name' => fake()->words(3, true),
            'type' => 'fixed',
            'status' => 'draft',
            'amount' => fake()->randomFloat(2, 5, 100),
            'percentage' => null,
            'priority' => fake()->numberBetween(0, 100),
            'is_stackable' => false,
            'starts_at' => null,
            'ends_at' => null,
            'conditions' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function percentage(float $percentage = 10): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'percentage',
            'amount' => null,
            'percentage' => $percentage,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }
}
