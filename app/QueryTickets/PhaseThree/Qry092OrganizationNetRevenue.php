<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry092OrganizationNetRevenue extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-092';
    }

    public function title(): string
    {
        return 'Organization net revenue';
    }

    public function description(): string
    {
        return 'Return every organization with successful payment count, '
            .'failed payment count, gross amount, refunded amount and net '
            .'revenue. Include organizations without payments and order by '
            .'organization name.';
    }

    public function concepts(): array
    {
        return [
            'leftJoin',
            'conditional aggregates',
            'COUNT',
            'SUM',
            'COALESCE',
            'GROUP BY',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'organization_name',
            'successful_payment_count',
            'failed_payment_count',
            'gross_amount',
            'refund_amount',
            'net_revenue',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-092 has not been solved yet.');
    }
}
