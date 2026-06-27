<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry104CustomersWithMultipleSalesOrders;

class Qry104CustomersWithMultipleSalesOrdersTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_customers_with_multiple_sales_orders(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry104CustomersWithMultipleSalesOrders::class,
        );

        $this->assertSame([
            'GV-0001',
        ], $results->pluck('customer_number')->all());

        $customer = $results->first();

        $this->assertSame(2, (int) $customer->sales_orders_count);
        $this->assertTrue($customer->relationLoaded('salesOrders'));
        $this->assertSame([
            'GV-SO-0001',
            'GV-SO-0004',
        ], $customer->salesOrders->pluck('order_number')->all());

        $this->assertExactColumns($results, [
            'id',
            'customer_number',
            'first_name',
            'last_name',
            'sales_orders_count',
        ]);
    }
}
