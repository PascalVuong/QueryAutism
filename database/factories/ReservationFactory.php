<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\Reservation;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = now()
            ->addDays(fake()->numberBetween(1, 30))
            ->setMinute(0)
            ->setSecond(0);

        $subtotal = fake()->randomFloat(2, 25, 250);

        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'venue_id' => Venue::factory(),
            'customer_id' => Customer::factory(),
            'created_by_user_id' => null,
            'reference_number' => strtoupper(
                fake()->unique()->bothify('RES-########'),
            ),
            'status' => 'pending',
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHour(),
            'party_size' => fake()->numberBetween(1, 8),
            'subtotal' => $subtotal,
            'discount_total' => 0,
            'total' => $subtotal,
            'currency' => 'EUR',
            'notes' => null,
            'cancelled_at' => null,
            'cancellation_reason' => null,
        ];
    }

    public function forCustomerAtVenue(
        Customer $customer,
        Venue $venue,
    ): static {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $customer->organization_id,
            'venue_id' => $venue->id,
            'customer_id' => $customer->id,
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => now()->subHour(),
            'cancellation_reason' => fake()->sentence(),
        ]);
    }

    public function noShow(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'no_show',
        ]);
    }
}
