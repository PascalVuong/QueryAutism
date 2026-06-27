<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry105SalesOrderTotalMismatches extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-105';
    }

    public function title(): string
    {
        return 'Sales order total mismatches';
    }

    public function description(): string
    {
        return 'Return sales orders whose stored total differs from the sum of '
            .'their item line totals. Include stored total, calculated item '
            .'total and the difference. Order by order number.';
    }

    public function concepts(): array
    {
        return [
            'leftJoin',
            'SUM',
            'COALESCE',
            'GROUP BY',
            'HAVING',
            'calculated column',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'order_number',
            'stored_total',
            'calculated_item_total',
            'difference',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-105 has not been solved yet.');
    }
}
