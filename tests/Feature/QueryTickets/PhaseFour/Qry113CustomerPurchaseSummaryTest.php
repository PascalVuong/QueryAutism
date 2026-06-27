<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry113CustomerPurchaseSummary;

class Qry113CustomerPurchaseSummaryTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_customer_purchase_summary(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry113CustomerPurchaseSummary::class,
        );

        $expected = [
            'GV-0001' => [2, 4, 110.0],
            'RP-0001' => [1, 4, 40.0],
            'SW-0001' => [1, 3, 90.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('customer_number')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->customer_number],
                [
                    (int) $row->completed_order_count,
                    (int) $row->purchased_quantity,
                    (float) $row->sales_revenue,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'customer_number',
            'completed_order_count',
            'purchased_quantity',
            'sales_revenue',
        ]);
    }
}
