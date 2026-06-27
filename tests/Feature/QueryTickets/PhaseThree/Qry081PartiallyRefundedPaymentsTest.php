<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry081PartiallyRefundedPayments;

class Qry081PartiallyRefundedPaymentsTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_partially_refunded_payments(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry081PartiallyRefundedPayments::class,
        );

        $this->assertCount(1, $results);

        $payment = $results->first();

        $this->assertSame('PAY-RP-0001', $payment->provider_reference);
        $this->assertSame('partially_refunded', $payment->status);
        $this->assertSame('80.00', $payment->amount);
        $this->assertSame('20.00', $payment->refunded_amount);
        $this->assertTrue($payment->relationLoaded('refunds'));
        $this->assertSame(
            ['20.00'],
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
