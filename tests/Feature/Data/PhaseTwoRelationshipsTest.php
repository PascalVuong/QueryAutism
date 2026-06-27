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

class PhaseTwoRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_two_relationships_are_configured(): void
    {
        $organization = Organization::factory()->active()->create();
        $customer = Customer::factory()->for($organization)->create();
        $venue = Venue::factory()->for($organization)->create();
        $facility = Facility::factory()->for($venue)->create();
        $resource = Resource::factory()->for($facility)->create();

        $reservation = Reservation::factory()
            ->forCustomerAtVenue($customer, $venue)
            ->create();

        $item = ReservationItem::factory()
            ->forReservationAndResource($reservation, $resource)
            ->create();

        $participant = ReservationParticipant::factory()
            ->forReservation($reservation)
            ->forCustomer($customer)
            ->create();

        $block = ResourceAvailabilityBlock::factory()
            ->forResource($resource)
            ->create();

        $history = ReservationStatusHistory::factory()
            ->for($reservation)
            ->create();

        $this->assertTrue($organization->venues->contains($venue));
        $this->assertTrue($organization->reservations->contains($reservation));
        $this->assertTrue($venue->organization->is($organization));
        $this->assertTrue($venue->facilities->contains($facility));
        $this->assertTrue($venue->reservations->contains($reservation));
        $this->assertTrue($facility->venue->is($venue));
        $this->assertTrue($facility->resources->contains($resource));
        $this->assertTrue($resource->facility->is($facility));
        $this->assertTrue($resource->reservationItems->contains($item));
        $this->assertTrue($resource->availabilityBlocks->contains($block));
        $this->assertTrue($resource->reservations->contains($reservation));
        $this->assertTrue($customer->reservations->contains($reservation));
        $this->assertTrue(
            $customer->reservationParticipants->contains($participant),
        );
        $this->assertTrue($reservation->items->contains($item));
        $this->assertTrue(
            $reservation->participants->contains($participant),
        );
        $this->assertTrue(
            $reservation->statusHistories->contains($history),
        );
        $this->assertTrue($reservation->resources->contains($resource));
        $this->assertTrue($item->reservation->is($reservation));
        $this->assertTrue($participant->customer->is($customer));
        $this->assertTrue($block->resource->is($resource));
        $this->assertTrue($history->reservation->is($reservation));
    }
}
