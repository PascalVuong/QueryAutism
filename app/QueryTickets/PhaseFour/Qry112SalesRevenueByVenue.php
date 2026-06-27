<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry112SalesRevenueByVenue extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-112';
    }

    public function title(): string
    {
        return 'Sales revenue by venue';
    }

    public function description(): string
    {
        return 'Return every venue with fulfilled or paid sales order count '
            .'and stored order revenue. Include venues without completed sales '
            .'and order by venue name.';
    }

    public function concepts(): array
    {
        return [
            'leftJoin',
            'conditional aggregates',
            'COUNT',
            'SUM',
            'COALESCE',
            'venue report',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'venue_name',
            'completed_order_count',
            'sales_revenue',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-112 has not been solved yet.');
    }
}
