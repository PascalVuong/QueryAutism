<?php

namespace Database\Factories;

use App\Models\MembershipPlan;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MembershipPlan>
 */
class MembershipPlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'code' => fake()->unique()->bothify('PLAN-####-??'),
            'name' => fake()->randomElement([
                'Guest',
                'Standard Member',
                'Premium Member',
                'Corporate Member',
                'Staff',
            ]).' '.fake()->unique()->numberBetween(1, 99999),
            'description' => fake()->optional()->sentence(),
            'status' => 'draft',
            'billing_interval' => 'none',
            'price' => fake()->randomFloat(2, 0, 1000),
            'currency' => 'EUR',
            'booking_window_days' => fake()->numberBetween(0, 90),
            'max_active_reservations' => fake()
                ->optional()
                ->numberBetween(1, 20),
            'max_guests_per_reservation' => fake()
                ->optional()
                ->numberBetween(0, 10),
            'priority' => fake()->numberBetween(0, 100),
            'valid_from' => fake()->optional()->dateTimeBetween(
                '-2 years',
                '+1 year',
            ),
            'valid_until' => null,
            'settings' => [
                'can_book_online' => fake()->boolean(),
                'can_join_waitlist' => fake()->boolean(),
            ],
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'valid_from' => now()->subYear(),
            'valid_until' => now()->addYear(),
        ]);
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

    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'billing_interval' => 'none',
            'price' => 0,
        ]);
    }

    public function monthly(): static
    {
        return $this->state(fn (array $attributes) => [
            'billing_interval' => 'monthly',
        ]);
    }

    public function yearly(): static
    {
        return $this->state(fn (array $attributes) => [
            'billing_interval' => 'yearly',
        ]);
    }
}
