<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry068ResourcesWithUpcomingReservationCount;

class Qry068ResourcesWithUpcomingReservationCountTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_upcoming_count_per_resource(): void
    {
        $results = $this->runTicket(
            Qry068ResourcesWithUpcomingReservationCount::class,
        );

        $this->assertSame([
            'North Course',
            'Lounge Table',
            'Padel Court 2',
            'Simulator 1',
            'Boardroom',
            'Padel Court 1',
            'Padel Court 3',
            'Restaurant Table A',
            'Treatment Room 1',
        ], $results->pluck('name')->all());

        $this->assertSame(
            [2, 1, 1, 1, 0, 0, 0, 0, 0],
            $results
                ->pluck('upcoming_reservation_items_count')
                ->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'code',
            'upcoming_reservation_items_count',
        ]);
    }
}