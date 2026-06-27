<?php

namespace Tests\Feature\Data;

use App\Models\Customer;
use App\Models\Facility;
use App\Models\Organization;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationParticipant;
use App\Models\ReservationStatusHistory;
use App\Models\Resource;
use App\Models\ResourceAvailabilityBlock;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseTwoFactoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_two_factories_and_states_create_valid_data(): void
    {
        $organization = Organization::factory()->active()->create();
        $customer = Customer::factory()->for($organization)->create();
        $venue = Venue::factory()->for($organization)->active()->create();
        $facility = Facility::factory()->for($venue)->active()->create();
        $resource = Resource::factory()
            ->for($facility)
            ->active()
            ->withCapacity(4)
            ->create();

        $reservation = Reservation::factory()
            ->forCustomerAtVenue($customer, $venue)
            ->confirmed()
            ->create();

        $item = ReservationItem::factory()
            ->forReservationAndResource($reservation, $resource)
            ->create();

        $participant = ReservationParticipant::factory()
            ->forReservation($reservation)
            ->forCustomer($customer)
            ->booker()
            ->create();

        $block = ResourceAvailabilityBlock::factory()
            ->forResource($resource)
            ->maintenance()
            ->create();

        $history = ReservationStatusHistory::factory()
            ->for($reservation)
            ->confirmed()
            ->create();

        $this->assertSame('active', $venue->status);
        $this->assertTrue($facility->venue->is($venue));
        $this->assertSame(4, $resource->capacity);
        $this->assertTrue($resource->is_bookable);
        $this->assertSame('confirmed', $reservation->status);
        $this->assertTrue($reservation->customer->is($customer));
        $this->assertTrue($item->resource->is($resource));
        $this->assertSame('booker', $participant->role);
        $this->assertSame('maintenance', $block->type);
        $this->assertSame('confirmed', $history->to_status);
    }
}
