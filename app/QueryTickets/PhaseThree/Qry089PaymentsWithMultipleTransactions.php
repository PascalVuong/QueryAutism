<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry089PaymentsWithMultipleTransactions extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-089';
    }

    public function title(): string
    {
        return 'Payments with multiple transactions';
    }

    public function description(): string
    {
        return 'Return payments that have at least two transactions. Include '
            .'transactions_count and order by that count descending, followed '
            .'by provider reference.';
    }

    public function concepts(): array
    {
        return [
            'has with count',
            'withCount',
            'orderByDesc',
            'relationship aggregate',
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
            'transactions_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-089 has not been solved yet.');
    }
}
