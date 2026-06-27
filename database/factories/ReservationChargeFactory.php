<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\ReservationCharge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservationCharge>
 */
class ReservationChargeFactory extends Factory
{
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 5, 150);

        return [
            'reservation_id' => Reservation::factory(),
            'reservation_item_id' => null,
            'price_rule_id' => null,
            'type' => 'base',
            'direction' => 'debit',
            'description' => fake()->words(3, true),
            'quantity' => 1,
            'unit_amount' => $amount,
            'total_amount' => $amount,
            'currency' => 'EUR',
            'metadata' => null,
        ];
    }

    public function discount(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'discount',
            'direction' => 'credit',
        ]);
    }

    public function surcharge(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'surcharge',
            'direction' => 'debit',
        ]);
    }
}
