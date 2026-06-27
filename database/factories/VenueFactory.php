<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Venue>
 */
class VenueFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company().' Venue';

        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(
                1000,
                999999,
            ),
            'status' => 'active',
            'timezone' => 'Europe/Amsterdam',
            'address_line_1' => fake()->streetAddress(),
            'city' => fake()->city(),
            'country_code' => 'NL',
            'settings' => [
                'allow_online_booking' => true,
            ],
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }
}
