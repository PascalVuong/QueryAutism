<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry090RefundReconciliation extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-090';
    }

    public function title(): string
    {
        return 'Refund reconciliation';
    }

    public function description(): string
    {
        return 'Return payments with refund records. Compare the stored '
            .'refunded amount with the sum of successful refunds and calculate '
            .'the remaining net amount. Order by provider reference.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'withSum',
            'aggregate alias',
            'where relationship',
            'calculated value',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'provider_reference',
            'payment_amount',
            'stored_refunded_amount',
            'successful_refund_total',
            'net_amount',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-090 has not been solved yet.');
    }
}
