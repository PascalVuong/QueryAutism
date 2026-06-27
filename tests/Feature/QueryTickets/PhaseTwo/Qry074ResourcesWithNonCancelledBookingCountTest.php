<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry074ResourcesWithNonCancelledBookingCount;

class Qry074ResourcesWithNonCancelledBookingCountTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_counts_non_cancelled_bookings_per_resource(): void
    {
        $results = $this->runTicket(
            Qry074ResourcesWithNonCancelledBookingCount::class,
        );

        $this->assertSame([
            'NORTH',
            'COURT-1',
            'COURT-2',
            'LOUNGE-TABLE',
            'ROOM-1',
            'SIM-1',
            'BOARDROOM',
            'COURT-3',
            'TABLE-A',
        ], $results->pluck('code')->all());

        $this->assertSame([
            2,
            1,
            1,
            1,
            1,
            1,
            0,
            0,
            0,
        ], $results->pluck(
            'non_cancelled_booking_count',
        )->map(fn ($count) => (int) $count)->all());

        $this->assertExactColumns($results, [
            'id',
            'code',
            'name',
            'non_cancelled_booking_count',
        ]);
    }
}
