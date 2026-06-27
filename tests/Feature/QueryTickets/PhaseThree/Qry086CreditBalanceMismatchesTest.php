<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry086CreditBalanceMismatches;

class Qry086CreditBalanceMismatchesTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_credit_balance_mismatches(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry086CreditBalanceMismatches::class,
        );

        $this->assertCount(1, $results);

        $row = $results->first();

        $this->assertSame('GV-0002', $row->customer_number);
        $this->assertSame('frozen', $row->status);
        $this->assertSame(15.0, (float) $row->stored_balance);
        $this->assertSame(20.0, (float) $row->calculated_balance);
        $this->assertSame(-5.0, (float) $row->difference);

        $this->assertResultColumns($results, [
            'customer_number',
            'status',
            'stored_balance',
            'calculated_balance',
            'difference',
        ]);
    }
}
