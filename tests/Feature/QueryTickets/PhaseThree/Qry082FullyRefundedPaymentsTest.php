<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry082FullyRefundedPayments;

class Qry082FullyRefundedPaymentsTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_fully_refunded_payments(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry082FullyRefundedPayments::class,
        );

        $this->assertCount(1, $results);

        $payment = $results->first();

        $this->assertSame('PAY-SW-0001', $payment->provider_reference);
        $this->assertSame('refunded', $payment->status);
        $this->assertSame($payment->amount, $payment->refunded_amount);
        $this->assertTrue($payment->relationLoaded('refunds'));
        $this->assertSame(
            ['90.00'],
            $payment->refunds->pluck('amount')->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'provider_reference',
            'status',
            'amount',
            'refunded_amount',
        ]);
    }
}
