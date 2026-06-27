<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry062ReservationsDuringAvailabilityBlocks;

class Qry062ReservationsDuringAvailabilityBlocksTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_reservations_during_blocks(): void
    {
        $results = $this->runTicket(
            Qry062ReservationsDuringAvailabilityBlocks::class,
        );

        $this->assertSame([
            'RP-RES-0002',
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