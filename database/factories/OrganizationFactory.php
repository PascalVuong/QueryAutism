<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'uuid' => Str::uuid(),
            'parent_id' => null,
            'name' => $name,
            'legal_name' => fake()->optional()->company(),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(
                1000,
                999999,
            ),
            'registration_number' => fake()->optional()->numerify('########'),
            'vat_number' => fake()->optional()->bothify('NL#########B##'),
            'email' => fake()->optional()->companyEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'status' => 'trial',
            'timezone' => 'Europe/Amsterdam',
            'currency' => 'EUR',
            'country_code' => 'NL',
            'settings' => [
                'allow_guest_bookings' => fake()->boolean(),
                'require_online_payment' => fake()->boolean(),
            ],
            'onboarded_at' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'onboarded_at' => fake()->dateTimeBetween('-3 years', '-1 day'),
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }

    public function childOf(Organization $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
        ]);
    }
}
