<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry091CustomerPaymentSummary;

class Qry091CustomerPaymentSummaryTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_customer_payment_summary(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry091CustomerPaymentSummary::class,
        );

        $expected = [
            'GV-0001' => [1, 0, 120.0, 0.0, 120.0],
            'GV-0002' => [1, 0, 60.0, 0.0, 60.0],
            'GV-0004' => [2, 1, 60.0, 0.0, 60.0],
            'RP-0001' => [1, 0, 80.0, 20.0, 60.0],
            'RP-0002' => [1, 1, 0.0, 0.0, 0.0],
            'SW-0001' => [1, 0, 90.0, 90.0, 0.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('customer_number')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->customer_number],
                [
                    (int) $row->payment_count,
                    (int) $row->failed_payment_count,
                    (float) $row->gross_paid,
                    (float) $row->refunded_total,
                    (float) $row->net_paid,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'customer_number',
            'payment_count',
            'failed_payment_count',
            'gross_paid',
            'refunded_total',
            'net_paid',
        ]);
    }
}
