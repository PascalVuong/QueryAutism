<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerProfile>
 */
class CustomerProfileFactory extends Factory
{
    public function definition(): array
    {
        $totalReservations = fake()->numberBetween(0, 80);
        $averageBookingValue = fake()->randomFloat(2, 20, 250);

        return [
            'customer_id' => Customer::factory(),
            'preferred_language' => fake()->randomElement(['en', 'nl']),
            'preferred_timezone' => 'Europe/Amsterdam',
            'preferred_currency' => 'EUR',
            'average_booking_value' => $averageBookingValue,
            'total_reservations' => $totalReservations,
            'total_spent' => round(
                $totalReservations * $averageBookingValue,
                2,
            ),
            'no_show_count' => fake()->numberBetween(0, 5),
            'cancellation_count' => fake()->numberBetween(0, 10),
            'loyalty_tier' => fake()->randomElement([
                'none',
                'bronze',
                'silver',
                'gold',
                'platinum',
            ]),
            'risk_score' => fake()->randomFloat(2, 0, 100),
            'preferences' => [
                'preferred_start_time' => fake()->randomElement([
                    'morning',
                    'afternoon',
                    'evening',
                ]),
                'allow_substitutes' => fake()->boolean(),
            ],
            'last_recalculated_at' => fake()->optional(0.85)->dateTimeBetween(
                '-90 days',
            ),
        ];
    }

    public function stale(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_recalculated_at' => now()->subDays(60),
        ]);
    }

    public function highRisk(): static
    {
        return $this->state(fn (array $attributes) => [
            'risk_score' => fake()->randomFloat(2, 80, 100),
        ]);
    }
}
