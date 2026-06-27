<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry090RefundReconciliation;

class Qry090RefundReconciliationTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_reconciles_refunds(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry090RefundReconciliation::class,
        );

        $this->assertSame([
            'PAY-RP-0001',
            'PAY-SW-0001',
        ], $results->pluck('provider_reference')->all());

        $expected = [
            'PAY-RP-0001' => [80.0, 20.0, 20.0, 60.0],
            'PAY-SW-0001' => [90.0, 90.0, 90.0, 0.0],
        ];

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->provider_reference],
                [
                    (float) $row->payment_amount,
                    (float) $row->stored_refunded_amount,
                    (float) $row->successful_refund_total,
                    (float) $row->net_amount,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'provider_reference',
            'payment_amount',
            'stored_refunded_amount',
            'successful_refund_total',
            'net_amount',
        ]);
    }
}
