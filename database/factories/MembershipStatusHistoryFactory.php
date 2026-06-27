<?php

namespace Database\Factories;

use App\Models\Membership;
use App\Models\MembershipStatusHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MembershipStatusHistory>
 */
class MembershipStatusHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'membership_id' => Membership::factory(),
            'from_status' => null,
            'to_status' => 'pending',
            'reason' => fake()->optional()->sentence(),
            'changed_by_user_id' => null,
            'effective_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'metadata' => [
                'source' => fake()->randomElement([
                    'system',
                    'staff',
                    'integration',
                ]),
            ],
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'pending',
            'to_status' => 'active',
        ]);
    }

    public function paused(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'active',
            'to_status' => 'paused',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'active',
            'to_status' => 'cancelled',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'active',
            'to_status' => 'expired',
        ]);
    }
}
