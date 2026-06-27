<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry104CustomersWithMultipleSalesOrders extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-104';
    }

    public function title(): string
    {
        return 'Customers with multiple sales orders';
    }

    public function description(): string
    {
        return 'Return customers with at least two sales orders. Include the '
            .'order count, eager load their orders by order number and order '
            .'customers by customer number.';
    }

    public function concepts(): array
    {
        return [
            'has with count',
            'withCount',
            'with',
            'constrained eager loading',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'customer_number',
            'first_name',
            'last_name',
            'sales_orders_count',
            'salesOrders.order_number',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-104 has not been solved yet.');
    }
}
