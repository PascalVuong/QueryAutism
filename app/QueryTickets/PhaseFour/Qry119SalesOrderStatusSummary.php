<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry119SalesOrderStatusSummary extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-119';
    }

    public function title(): string
    {
        return 'Sales order status summary';
    }

    public function description(): string
    {
        return 'Group sales orders by status. Return the order count and '
            .'stored order total for each status, ordered alphabetically by '
            .'status.';
    }

    public function concepts(): array
    {
        return [
            'GROUP BY',
            'COUNT',
            'SUM',
            'status report',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'status',
            'order_count',
            'order_total',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-119 has not been solved yet.');
    }
}
