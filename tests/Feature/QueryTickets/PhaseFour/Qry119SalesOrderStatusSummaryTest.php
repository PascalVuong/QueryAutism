<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry119SalesOrderStatusSummary;

class Qry119SalesOrderStatusSummaryTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_sales_order_status_summary(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry119SalesOrderStatusSummary::class,
        );

        $expected = [
            'cancelled' => [1, 25.0],
            'draft' => [1, 0.0],
            'fulfilled' => [3, 150.0],
            'paid' => [1, 90.0],
            'pending' => [1, 20.0],
            'refunded' => [1, 12.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('status')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->status],
                [
                    (int) $row->order_count,
                    (float) $row->order_total,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'status',
            'order_count',
            'order_total',
        ]);
    }
}
