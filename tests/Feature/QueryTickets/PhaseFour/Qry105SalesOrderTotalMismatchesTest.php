<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry105SalesOrderTotalMismatches;

class Qry105SalesOrderTotalMismatchesTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_sales_order_total_mismatches(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry105SalesOrderTotalMismatches::class,
        );

        $this->assertCount(1, $results);

        $row = $results->first();

        $this->assertSame('SW-SO-0001', $row->order_number);
        $this->assertSame(90.0, (float) $row->stored_total);
        $this->assertSame(86.0, (float) $row->calculated_item_total);
        $this->assertSame(4.0, (float) $row->difference);

        $this->assertResultColumns($results, [
            'order_number',
            'stored_total',
            'calculated_item_total',
            'difference',
        ]);
    }
}
