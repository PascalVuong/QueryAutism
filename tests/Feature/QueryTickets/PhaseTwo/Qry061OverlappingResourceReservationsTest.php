<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry061OverlappingResourceReservations;

class Qry061OverlappingResourceReservationsTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_overlapping_reservations(): void
    {
        $results = $this->runTicket(
            Qry061OverlappingResourceReservations::class,
        );

        $this->assertSame([
            'GV-RES-0001',
            'GV-RES-0002',
        ], $results->pluck('reference_number')->all());

        $this->assertExactColumns($results, [
            'id',
            'reference_number',
            'status',
            'starts_at',
            'ends_at',
        ]);
    }
}