<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Reservation;
use App\Models\ReservationParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservationParticipant>
 */
class ReservationParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'customer_id' => null,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->optional()->safeEmail(),
            'role' => 'guest',
            'status' => 'registered',
            'checked_in_at' => null,
        ];
    }

    public function forReservation(Reservation $reservation): static
    {
        return $this->state(fn (array $attributes) => [
            'reservation_id' => $reservation->id,
        ]);
    }

    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
        ]);
    }

    public function booker(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'booker',
        ]);
    }

    public function attended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'attended',
            'checked_in_at' => now(),
        ]);
    }

    public function noShow(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'no_show',
            'checked_in_at' => null,
        ]);
    }
}
