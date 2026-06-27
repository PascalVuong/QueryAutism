<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry109BestSellingVariants extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-109';
    }

    public function title(): string
    {
        return 'Best-selling variants';
    }

    public function description(): string
    {
        return 'Return variants sold through fulfilled or paid sales orders. '
            .'Exclude cancelled and refunded items. Include sold quantity and '
            .'line revenue. Order by sold quantity descending and then by SKU.';
    }

    public function concepts(): array
    {
        return [
            'join',
            'whereIn',
            'conditional filtering',
            'SUM',
            'GROUP BY',
            'orderByDesc',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'sku',
            'variant_name',
            'sold_quantity',
            'line_revenue',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-109 has not been solved yet.');
    }
}
