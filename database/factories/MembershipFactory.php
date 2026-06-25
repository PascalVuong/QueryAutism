<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Membership>
 */
class MembershipFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('-2 years', '+3 months');

        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'customer_id' => Customer::factory(),
            'membership_plan_id' => MembershipPlan::factory(),
            'membership_number' => fake()
                ->unique()
                ->numerify('MEM-########'),
            'status' => 'pending',
            'starts_at' => $startsAt,
            'ends_at' => null,
            'activated_at' => null,
            'cancelled_at' => null,
            'cancellation_reason' => null,
            'auto_renew' => false,
            'agreed_price' => fake()->randomFloat(2, 0, 1000),
            'currency' => 'EUR',
        ];
    }

    public function forOrganization(Organization $organization): static
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $organization->id,
        ]);
    }

    public function forCustomerAndPlan(
        Customer $customer,
        MembershipPlan $plan,
    ): static {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $customer->organization_id,
            'customer_id' => $customer->id,
            'membership_plan_id' => $plan->id,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'starts_at' => now()->subMonths(3),
            'ends_at' => now()->addYear(),
            'activated_at' => now()->subMonths(3),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'starts_at' => now()->addMonth(),
            'ends_at' => now()->addYear(),
            'activated_at' => null,
        ]);
    }

    public function paused(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paused',
            'starts_at' => now()->subYear(),
            'ends_at' => now()->addYear(),
            'activated_at' => now()->subYear(),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'starts_at' => now()->subYears(2),
            'ends_at' => now()->subDay(),
            'activated_at' => now()->subYears(2),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'starts_at' => now()->subYear(),
            'ends_at' => now()->addMonth(),
            'activated_at' => now()->subYear(),
            'cancelled_at' => now()->subWeek(),
            'cancellation_reason' => fake()->sentence(),
        ]);
    }

    public function autoRenewing(): static
    {
        return $this->state(fn (array $attributes) => [
            'auto_renew' => true,
        ]);
    }
}
