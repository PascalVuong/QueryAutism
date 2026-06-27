<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry103SalesOrdersWithItemCount extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-103';
    }

    public function title(): string
    {
        return 'Sales orders with item count';
    }

    public function description(): string
    {
        return 'Return every sales order with its number of items. Include '
            .'orders without items and order by order number.';
    }

    public function concepts(): array
    {
        return [
            'withCount',
            'relationship aggregate',
            'select',
            'include zero counts',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'order_number',
            'status',
            'total',
            'items_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-103 has not been solved yet.');
    }
}
