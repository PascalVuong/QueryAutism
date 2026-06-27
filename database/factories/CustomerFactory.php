<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'user_id' => null,
            'customer_number' => fake()
                ->unique()
                ->numerify('CUS-######'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->optional(0.85)->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'date_of_birth' => fake()->optional()->dateTimeBetween(
                '-85 years',
                '-16 years',
            ),
            'status' => 'active',
            'source' => 'guest',
            'marketing_consent' => fake()->boolean(30),
            'registered_at' => fake()->optional(0.8)->dateTimeBetween(
                '-5 years',
            ),
            'last_activity_at' => fake()->optional(0.8)->dateTimeBetween(
                '-1 year',
            ),
            'notes' => fake()->optional(0.15)->sentence(),
        ];
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'source' => 'guest',
        ]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
            'email' => $user->email,
            'source' => 'online',
        ]);
    }

    public function blocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'blocked',
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
}
