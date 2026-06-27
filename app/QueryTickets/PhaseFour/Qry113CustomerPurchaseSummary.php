<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry113CustomerPurchaseSummary extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-113';
    }

    public function title(): string
    {
        return 'Customer purchase summary';
    }

    public function description(): string
    {
        return 'Return customers with at least one fulfilled or paid sales '
            .'order. Include completed order count, purchased item quantity '
            .'and stored order revenue. Order by customer number.';
    }

    public function concepts(): array
    {
        return [
            'joinSub',
            'conditional aggregate',
            'COUNT DISTINCT',
            'SUM',
            'GROUP BY',
            'customer report',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'customer_number',
            'completed_order_count',
            'purchased_quantity',
            'sales_revenue',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-113 has not been solved yet.');
    }
}
