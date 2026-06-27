<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\SalesOrder;
use App\Models\StockLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SalesOrder>
 */
class SalesOrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10, 250);

        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'venue_id' => null,
            'customer_id' => null,
            'reservation_id' => null,
            'stock_location_id' => null,
            'order_number' => strtoupper(
                fake()->unique()->bothify('SO-########'),
            ),
            'status' => 'draft',
            'ordered_at' => now(),
            'fulfilled_at' => null,
            'cancelled_at' => null,
            'subtotal' => $subtotal,
            'discount_total' => 0,
            'tax_total' => 0,
            'total' => $subtotal,
            'currency' => 'EUR',
            'notes' => null,
        ];
    }

    public function forCustomerAtLocation(
        Customer $customer,
        StockLocation $location,
    ): static {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $customer->organization_id,
            'venue_id' => $location->venue_id,
            'customer_id' => $customer->id,
            'stock_location_id' => $location->id,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
        ]);
    }

    public function fulfilled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'fulfilled',
            'fulfilled_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refunded',
        ]);
    }
}
