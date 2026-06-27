<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry094PaymentMethodBreakdown;

class Qry094PaymentMethodBreakdownTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_payment_method_breakdown(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry094PaymentMethodBreakdown::class,
        );

        $expected = [
            'card' => [6, 2, 350.0, 110.0, 240.0],
            'cash' => [1, 0, 60.0, 0.0, 60.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('method')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->method],
                [
                    (int) $row->payment_count,
                    (int) $row->failed_payment_count,
                    (float) $row->gross_amount,
                    (float) $row->refund_amount,
                    (float) $row->net_amount,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'method',
            'payment_count',
            'failed_payment_count',
            'gross_amount',
            'refund_amount',
            'net_amount',
        ]);
    }
}
