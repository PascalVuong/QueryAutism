<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry069ReservationItemsWithResourceLocation;

class Qry069ReservationItemsWithResourceLocationTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_eager_loads_nested_resource_location(): void
    {
        $results = $this->runTicket(
            Qry069ReservationItemsWithResourceLocation::class,
        );

        $this->assertSame([
            'GV-RES-0001',
            'GV-RES-0002',
            'RP-RES-0001',
            'GV-RES-0003',
            'RP-RES-0002',
            'SW-RES-0001',
            'GV-RES-0004',
            'RP-RES-0003',
        ], $results->pluck('reservation.reference_number')->all());

        foreach ($results as $item) {
            $this->assertTrue($item->relationLoaded('reservation'));
            $this->assertTrue($item->relationLoaded('resource'));
            $this->assertTrue($item->resource->relationLoaded('facility'));
            $this->assertTrue(
                $item->resource->facility->relationLoaded('venue'),
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'reservation_id',
            'resource_id',
            'starts_at',
            'ends_at',
            'status',
        ]);
    }
}