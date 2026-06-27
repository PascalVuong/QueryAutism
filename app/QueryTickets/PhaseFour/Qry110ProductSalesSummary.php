<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry110ProductSalesSummary extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-110';
    }

    public function title(): string
    {
        return 'Product sales summary';
    }

    public function description(): string
    {
        return 'Return every product with fulfilled or paid sales quantity '
            .'and line revenue. Exclude cancelled and refunded items, include '
            .'products without sales and order by product code.';
    }

    public function concepts(): array
    {
        return [
            'leftJoinSub',
            'conditional aggregate',
            'SUM',
            'COALESCE',
            'GROUP BY',
            'include zero totals',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'product_code',
            'product_name',
            'sold_quantity',
            'line_revenue',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-110 has not been solved yet.');
    }
}
