<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'organization_id' => fn () => Reservation::factory()->create()
                ->organization_id,
            'reservation_id' => Reservation::factory(),
            'customer_id' => fn (array $attributes) => Reservation::query()
                ->findOrFail($attributes['reservation_id'])
                ->customer_id,
            'status' => 'pending',
            'method' => 'card',
            'amount' => fake()->randomFloat(2, 10, 250),
            'refunded_amount' => 0,
            'currency' => 'EUR',
            'provider_reference' => null,
            'paid_at' => null,
            'failed_at' => null,
            'metadata' => null,
        ];
    }

    public function forReservation(Reservation $reservation): static
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $reservation->organization_id,
            'reservation_id' => $reservation->id,
            'customer_id' => $reservation->customer_id,
            'currency' => $reservation->currency,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
            'failed_at' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'paid_at' => null,
            'failed_at' => now(),
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refunded',
            'paid_at' => now()->subDay(),
            'refunded_amount' => $attributes['amount'] ?? 0,
        ]);
    }
}
