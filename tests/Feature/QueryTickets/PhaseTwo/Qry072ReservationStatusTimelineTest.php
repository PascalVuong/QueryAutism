<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry072ReservationStatusTimeline;

class Qry072ReservationStatusTimelineTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_the_reservation_status_timeline(): void
    {
        $results = $this->runTicket(
            Qry072ReservationStatusTimeline::class,
        );

        $this->assertSame([
            'pending',
            'confirmed',
        ], $results->pluck('to_status')->all());

        $this->assertSame([
            null,
            'pending',
        ], $results->pluck('from_status')->all());

        $this->assertTrue(
            $results->first()->effective_at->lte(
                $results->last()->effective_at,
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'reservation_id',
            'from_status',
            'to_status',
            'effective_at',
        ]);
    }
}
