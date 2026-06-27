<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry056ReservationsWithCustomerAndVenue;

class Qry056ReservationsWithCustomerAndVenueTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_eager_loads_customer_and_venue(): void
    {
        $results = $this->runPhaseTwoTicket(
            Qry056ReservationsWithCustomerAndVenue::class,
        );

        $this->assertSame([
            'GV-RES-0001',
            'GV-RES-0002',
            'GV-RES-0003',
            'GV-RES-0004',
            'RP-RES-0001',
            'RP-RES-0002',
            'RP-RES-0003',
            'SW-RES-0001',
        ], $results->pluck('reference_number')->all());

        foreach ($results as $reservation) {
            $this->assertTrue($reservation->relationLoaded('customer'));
            $this->assertTrue($reservation->relationLoaded('venue'));

            $this->assertSame([
                'id',
                'customer_number',
                'first_name',
                'last_name',
                'email',
            ], array_keys($reservation->customer->getAttributes()));

            $this->assertSame([
                'id',
                'name',
                'slug',
                'city',
            ], array_keys($reservation->venue->getAttributes()));
        }

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'venue_id',
            'customer_id',
            'reference_number',
            'status',
            'starts_at',
            'ends_at',
            'total',
        ]);
    }
}