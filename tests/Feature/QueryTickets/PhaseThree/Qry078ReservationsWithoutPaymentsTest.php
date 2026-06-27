<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry078ReservationsWithoutPayments;

class Qry078ReservationsWithoutPaymentsTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_reservations_without_payments(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry078ReservationsWithoutPayments::class,
        );

        $this->assertSame([
            'GV-RES-0003',
            'RP-RES-0003',
        ], $results->pluck('reference_number')->all());

        $this->assertExactColumns($results, [
            'id',
            'reference_number',
            'status',
            'total',
        ]);
    }
}
