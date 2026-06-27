<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry103SalesOrdersWithItemCount;

class Qry103SalesOrdersWithItemCountTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_item_count_per_sales_order(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry103SalesOrdersWithItemCount::class,
        );

        $this->assertSame([
            'GV-SO-0001',
            'GV-SO-0002',
            'GV-SO-0003',
            'GV-SO-0004',
            'RP-SO-0001',
            'RP-SO-0002',
            'SW-SO-0001',
            'VO-SO-0001',
        ], $results->pluck('order_number')->all());

        $this->assertSame([
            2,
            1,
            1,
            1,
            2,
            1,
            2,
            0,
        ], $results->pluck('items_count')->map(
            fn ($count) => (int) $count,
        )->all());

        $this->assertExactColumns($results, [
            'id',
            'order_number',
            'status',
            'total',
            'items_count',
        ]);
    }
}
