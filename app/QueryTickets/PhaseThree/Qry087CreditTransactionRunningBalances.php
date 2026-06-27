<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry087CreditTransactionRunningBalances extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-087';
    }

    public function title(): string
    {
        return 'Credit transaction running balances';
    }

    public function description(): string
    {
        return 'Return every credit transaction with its customer number and '
            .'a calculated running balance per credit account. Order by '
            .'customer number, occurred_at and id.';
    }

    public function concepts(): array
    {
        return [
            'window function',
            'SUM OVER',
            'PARTITION BY',
            'ORDER BY',
            'join',
            'running total',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'customer_number',
            'type',
            'amount',
            'balance_after',
            'running_balance',
            'occurred_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-087 has not been solved yet.');
    }
}
