<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry081PartiallyRefundedPayments extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-081';
    }

    public function title(): string
    {
        return 'Partially refunded payments';
    }

    public function description(): string
    {
        return 'Return payments where refunded_amount is greater than zero '
            .'but lower than amount. Eager load successful refunds and order '
            .'by provider reference.';
    }

    public function concepts(): array
    {
        return [
            'whereColumn',
            'where',
            'with',
            'constrained eager loading',
            'relationship data',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'provider_reference',
            'status',
            'amount',
            'refunded_amount',
            'refunds.amount',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-081 has not been solved yet.');
    }
}
