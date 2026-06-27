<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry095FinancialHealthReport extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-095';
    }

    public function title(): string
    {
        return 'Financial health report';
    }

    public function description(): string
    {
        return 'Return every organization with reservation count, successful '
            .'and failed payment counts, underpaid reservation count, net '
            .'revenue and total stored credit balance. Include organizations '
            .'without financial activity and order by organization name.';
    }

    public function concepts(): array
    {
        return [
            'subqueries',
            'conditional aggregates',
            'correlated aggregation',
            'COALESCE',
            'financial reconciliation',
            'multi-tenant report',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'organization_name',
            'reservation_count',
            'successful_payment_count',
            'failed_payment_count',
            'underpaid_reservation_count',
            'net_revenue',
            'credit_balance_total',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-095 has not been solved yet.');
    }
}
