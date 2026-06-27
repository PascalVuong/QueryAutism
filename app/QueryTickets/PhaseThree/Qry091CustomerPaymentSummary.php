<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry091CustomerPaymentSummary extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-091';
    }

    public function title(): string
    {
        return 'Customer payment summary';
    }

    public function description(): string
    {
        return 'Return customers with payments and summarize payment count, '
            .'failed count, successful gross amount, refunds and net paid '
            .'amount. Order by customer number.';
    }

    public function concepts(): array
    {
        return [
            'join',
            'conditional aggregates',
            'COUNT',
            'SUM',
            'CASE WHEN',
            'GROUP BY',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'customer_number',
            'payment_count',
            'failed_payment_count',
            'gross_paid',
            'refunded_total',
            'net_paid',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-091 has not been solved yet.');
    }
}
