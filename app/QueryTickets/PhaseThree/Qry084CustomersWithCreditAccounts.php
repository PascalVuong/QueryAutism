<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry084CustomersWithCreditAccounts extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-084';
    }

    public function title(): string
    {
        return 'Customers with credit accounts';
    }

    public function description(): string
    {
        return 'Return customers that have credit accounts. Eager load the '
            .'accounts ordered by currency and order customers by customer '
            .'number.';
    }

    public function concepts(): array
    {
        return [
            'has',
            'with',
            'constrained eager loading',
            'orderBy',
            'relationship query',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'customer_number',
            'first_name',
            'last_name',
            'creditAccounts.status',
            'creditAccounts.balance',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-084 has not been solved yet.');
    }
}
