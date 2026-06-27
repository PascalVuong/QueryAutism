<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry067VenuesWithReservationCount;

class Qry067VenuesWithReservationCountTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_reservation_count_per_venue(): void
    {
        $results = $this->runTicket(
            Qry067VenuesWithReservationCount::class,
        );

        $this->assertSame([
            'Green Valley Main Venue',
            'Rotterdam Padel Hall',
            'Serenity Spa',
            'VenueOps Training Centre',
        ], $results->pluck('name')->all());

        $this->assertSame(
            [4, 3, 1, 0],
            $results->pluck('reservations_count')->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'slug',
            'reservations_count',
        ]);
    }
}