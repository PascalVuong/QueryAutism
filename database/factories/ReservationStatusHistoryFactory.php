<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\ReservationStatusHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservationStatusHistory>
 */
class ReservationStatusHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'from_status' => null,
            'to_status' => 'pending',
            'reason' => null,
            'changed_by_user_id' => null,
            'effective_at' => now(),
            'metadata' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'pending',
            'to_status' => 'confirmed',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'confirmed',
            'to_status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'confirmed',
            'to_status' => 'cancelled',
        ]);
    }

    public function noShow(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'confirmed',
            'to_status' => 'no_show',
        ]);
    }
}
