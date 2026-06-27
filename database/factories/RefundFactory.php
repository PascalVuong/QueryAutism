<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Refund>
 */
class RefundFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'payment_id' => Payment::factory(),
            'reservation_id' => fn (array $attributes) => Payment::query()
                ->findOrFail($attributes['payment_id'])
                ->reservation_id,
            'requested_by_user_id' => null,
            'status' => 'pending',
            'amount' => fake()->randomFloat(2, 5, 100),
            'currency' => 'EUR',
            'reason' => fake()->optional()->sentence(),
            'provider_reference' => null,
            'processed_at' => null,
            'metadata' => null,
        ];
    }

    public function forPayment(Payment $payment): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_id' => $payment->id,
            'reservation_id' => $payment->reservation_id,
            'currency' => $payment->currency,
        ]);
    }

    public function succeeded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'succeeded',
            'processed_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'processed_at' => now(),
        ]);
    }
}
