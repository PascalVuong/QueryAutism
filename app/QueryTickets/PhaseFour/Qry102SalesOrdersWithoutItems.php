<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry102SalesOrdersWithoutItems extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-102';
    }

    public function title(): string
    {
        return 'Sales orders without items';
    }

    public function description(): string
    {
        return 'Return sales orders that do not have any items. Order by order '
            .'number.';
    }

    public function concepts(): array
    {
        return [
            'doesntHave',
            'NOT EXISTS',
            'relationship query',
            'select',
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
            'ordered_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-102 has not been solved yet.');
    }
}
