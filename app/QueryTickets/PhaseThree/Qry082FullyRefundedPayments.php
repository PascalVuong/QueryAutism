<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry082FullyRefundedPayments extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-082';
    }

    public function title(): string
    {
        return 'Fully refunded payments';
    }

    public function description(): string
    {
        return 'Return payments whose refunded amount equals the original '
            .'amount and is greater than zero. Eager load successful refunds.';
    }

    public function concepts(): array
    {
        return [
            'whereColumn',
            'where',
            'with',
            'relationship query',
            'select',
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
        throw new LogicException('QRY-082 has not been solved yet.');
    }
}
