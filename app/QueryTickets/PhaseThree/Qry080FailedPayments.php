<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry080FailedPayments extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-080';
    }

    public function title(): string
    {
        return 'Failed payments';
    }

    public function description(): string
    {
        return 'Return failed payments with only their failed transactions '
            .'eager loaded. Order by failed_at descending.';
    }

    public function concepts(): array
    {
        return [
            'where',
            'whereHas',
            'constrained eager loading',
            'with',
            'latest',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'provider_reference',
            'status',
            'amount',
            'failed_at',
            'transactions.type',
            'transactions.failure_reason',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-080 has not been solved yet.');
    }
}
