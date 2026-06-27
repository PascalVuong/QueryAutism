<?php

namespace Database\Factories;

use App\Models\CreditAccount;
use App\Models\Customer;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CreditAccount>
 */
class CreditAccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'customer_id' => Customer::factory(),
            'status' => 'active',
            'balance' => fake()->randomFloat(2, 0, 200),
            'currency' => 'EUR',
            'expires_at' => null,
            'metadata' => null,
        ];
    }

    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $customer->organization_id,
            'customer_id' => $customer->id,
        ]);
    }

    public function frozen(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'frozen',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
        ]);
    }
}
