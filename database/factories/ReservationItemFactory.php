<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservationItem>
 */
class ReservationItemFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = now()->addDay()->setMinute(0)->setSecond(0);
        $unitPrice = fake()->randomFloat(2, 10, 100);

        return [
            'reservation_id' => Reservation::factory(),
            'resource_id' => Resource::factory(),
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHour(),
            'quantity' => 1,
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice,
            'status' => 'reserved',
            'notes' => null,
        ];
    }

    public function forReservationAndResource(
        Reservation $reservation,
        Resource $resource,
    ): static {
        return $this->state(fn (array $attributes) => [
            'reservation_id' => $reservation->id,
            'resource_id' => $resource->id,
            'starts_at' => $reservation->starts_at,
            'ends_at' => $reservation->ends_at,
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
        ]);
    }
}
