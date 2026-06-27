<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'uuid' => Str::uuid(),
            'venue_id' => Venue::factory(),
            'code' => strtoupper(fake()->unique()->bothify('FAC-####')),
            'name' => ucfirst($name),
            'type' => fake()->randomElement([
                'course',
                'court',
                'hospitality',
                'wellness',
                'meeting',
            ]),
            'status' => 'active',
            'settings' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }
}
