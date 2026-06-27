<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Resource>
 */
class ResourceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'facility_id' => Facility::factory(),
            'code' => strtoupper(fake()->unique()->bothify('RES-####')),
            'name' => ucfirst(fake()->unique()->words(2, true)),
            'type' => fake()->randomElement([
                'course',
                'court',
                'room',
                'table',
                'simulator',
            ]),
            'status' => 'active',
            'capacity' => fake()->numberBetween(1, 12),
            'is_bookable' => true,
            'settings' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'is_bookable' => true,
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
            'is_bookable' => false,
        ]);
    }

    public function withCapacity(int $capacity): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => $capacity,
        ]);
    }
}
