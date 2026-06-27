<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentTransaction>
 */
class PaymentTransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'type' => 'capture',
            'status' => 'succeeded',
            'amount' => fake()->randomFloat(2, 10, 250),
            'currency' => 'EUR',
            'provider_reference' => null,
            'failure_reason' => null,
            'occurred_at' => now(),
            'metadata' => null,
        ];
    }

    public function forPayment(Payment $payment): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_id' => $payment->id,
            'currency' => $payment->currency,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'failure',
            'status' => 'failed',
            'failure_reason' => fake()->sentence(),
        ]);
    }

    public function refund(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'refund',
            'status' => 'succeeded',
        ]);
    }
}
