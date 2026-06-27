<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry080FailedPayments;

class Qry080FailedPaymentsTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_failed_payments(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry080FailedPayments::class,
        );

        $this->assertSame([
            'PAY-GV-0004-B',
            'PAY-RP-0002',
        ], $results->pluck('provider_reference')->all());

        foreach ($results as $payment) {
            $this->assertTrue($payment->relationLoaded('transactions'));
            $this->assertCount(1, $payment->transactions);
            $this->assertSame(
                'failure',
                $payment->transactions->first()->type,
            );
            $this->assertNotNull(
                $payment->transactions->first()->failure_reason,
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'provider_reference',
            'status',
            'amount',
            'failed_at',
        ]);
    }
}
