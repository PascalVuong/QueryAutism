<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry088PaymentTransactionTimeline;

class Qry088PaymentTransactionTimelineTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_payment_transaction_timeline(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry088PaymentTransactionTimeline::class,
        );

        $this->assertSame([
            'authorization',
            'capture',
            'refund',
        ], $results->pluck('type')->all());

        $this->assertSame([
            80.0,
            80.0,
            20.0,
        ], $results->map(
            fn ($transaction) => (float) $transaction->amount,
        )->all());

        $this->assertTrue(
            $results->first()->occurred_at->lte(
                $results->last()->occurred_at,
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'type',
            'status',
            'amount',
            'occurred_at',
            'provider_reference',
        ]);
    }
}
