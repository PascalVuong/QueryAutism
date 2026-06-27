<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\ExternalIdentifier;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExternalIdentifier>
 */
class ExternalIdentifierFactory extends Factory
{
    public function definition(): array
    {
        $value = fake()->unique()->bothify('EXT-########');

        return [
            'organization_id' => Organization::factory(),
            'customer_id' => Customer::factory(),
            'provider' => fake()->randomElement([
                'golf_federation',
                'crm',
                'loyalty_provider',
                'booking_partner',
            ]),
            'identifier_type' => fake()->randomElement([
                'membership_number',
                'customer_number',
                'loyalty_number',
            ]),
            'identifier_value' => $value,
            'normalized_value' => strtoupper(trim($value)),
            'verified_at' => null,
            'verification_source' => null,
            'last_checked_at' => null,
            'metadata' => null,
        ];
    }

    public function forOrganization(Organization $organization): static
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $organization->id,
        ]);
    }

    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $customer->organization_id,
            'customer_id' => $customer->id,
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => now(),
            'verification_source' => 'api',
            'last_checked_at' => now(),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => null,
            'verification_source' => null,
        ]);
    }

    public function manuallyVerified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => now(),
            'verification_source' => 'manual',
            'last_checked_at' => now(),
        ]);
    }
}
