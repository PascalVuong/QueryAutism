<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry079UnderpaidReservations;

class Qry079UnderpaidReservationsTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_underpaid_reservations(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry079UnderpaidReservations::class,
        );

        $this->assertCount(1, $results);

        $row = $results->first();

        $this->assertSame('GV-RES-0004', $row->reference_number);
        $this->assertSame(100.0, (float) $row->total);
        $this->assertSame(60.0, (float) $row->paid_amount);
        $this->assertSame(40.0, (float) $row->amount_due);

        $this->assertResultColumns($results, [
            'reference_number',
            'total',
            'paid_amount',
            'amount_due',
        ]);
    }
}
