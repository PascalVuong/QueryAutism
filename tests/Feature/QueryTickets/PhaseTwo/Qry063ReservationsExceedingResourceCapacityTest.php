<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry063ReservationsExceedingResourceCapacity;

class Qry063ReservationsExceedingResourceCapacityTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_over_capacity_reservations(): void
    {
        $results = $this->runTicket(
            Qry063ReservationsExceedingResourceCapacity::class,
        );

        $this->assertSame([
            'GV-RES-0004',
        ], $results->pluck('reference_number')->all());

        $this->assertSame([5], $results->pluck('party_size')->all());

        $this->assertExactColumns($results, [
            'id',
            'reference_number',
            'party_size',
            'status',
        ]);
    }
}