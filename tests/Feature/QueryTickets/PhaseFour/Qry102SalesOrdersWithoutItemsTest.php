<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry102SalesOrdersWithoutItems;

class Qry102SalesOrdersWithoutItemsTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_sales_orders_without_items(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry102SalesOrdersWithoutItems::class,
        );

        $this->assertSame([
            'VO-SO-0001',
        ], $results->pluck('order_number')->all());

        $this->assertSame('draft', $results->first()->status);
        $this->assertSame('0.00', $results->first()->total);
        $this->assertNull($results->first()->ordered_at);

        $this->assertExactColumns($results, [
            'id',
            'order_number',
            'status',
            'total',
            'ordered_at',
        ]);
    }
}
