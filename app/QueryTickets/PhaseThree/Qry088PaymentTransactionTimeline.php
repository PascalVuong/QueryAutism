<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry088PaymentTransactionTimeline extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-088';
    }

    public function title(): string
    {
        return 'Payment transaction timeline';
    }

    public function description(): string
    {
        return 'Return the transaction timeline for payment PAY-RP-0001. '
            .'Order by occurred_at and then by id.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'relationship filtering',
            'history table',
            'orderBy',
            'select',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'type',
            'status',
            'amount',
            'occurred_at',
            'provider_reference',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-088 has not been solved yet.');
    }
}
