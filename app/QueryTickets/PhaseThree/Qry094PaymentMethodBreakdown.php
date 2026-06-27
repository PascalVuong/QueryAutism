<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry094PaymentMethodBreakdown extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-094';
    }

    public function title(): string
    {
        return 'Payment method breakdown';
    }

    public function description(): string
    {
        return 'Group payments by method and return payment count, failed '
            .'count, successful gross amount, refunded amount and net amount. '
            .'Order by method.';
    }

    public function concepts(): array
    {
        return [
            'GROUP BY',
            'conditional aggregates',
            'COUNT',
            'SUM',
            'CASE WHEN',
            'financial report',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'method',
            'payment_count',
            'failed_payment_count',
            'gross_amount',
            'refund_amount',
            'net_amount',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-094 has not been solved yet.');
    }
}
