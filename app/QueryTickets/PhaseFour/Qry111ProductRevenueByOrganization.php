<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry111ProductRevenueByOrganization extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-111';
    }

    public function title(): string
    {
        return 'Product revenue by organization';
    }

    public function description(): string
    {
        return 'Return every organization with fulfilled or paid product line '
            .'quantity and line revenue. Exclude cancelled and refunded items, '
            .'include organizations without product sales and order by name.';
    }

    public function concepts(): array
    {
        return [
            'leftJoinSub',
            'multi-table aggregate',
            'SUM',
            'COALESCE',
            'GROUP BY',
            'organization report',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'organization_name',
            'sold_quantity',
            'line_revenue',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-111 has not been solved yet.');
    }
}
