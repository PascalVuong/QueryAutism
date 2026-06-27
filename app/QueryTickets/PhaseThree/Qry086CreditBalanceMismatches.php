<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry086CreditBalanceMismatches extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-086';
    }

    public function title(): string
    {
        return 'Credit balance mismatches';
    }

    public function description(): string
    {
        return 'Return credit accounts whose stored balance differs from the '
            .'sum of their transactions. Include the customer number, both '
            .'balances and the difference. Order by customer number.';
    }

    public function concepts(): array
    {
        return [
            'leftJoin',
            'SUM',
            'COALESCE',
            'GROUP BY',
            'HAVING',
            'calculated column',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'customer_number',
            'status',
            'stored_balance',
            'calculated_balance',
            'difference',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-086 has not been solved yet.');
    }
}
