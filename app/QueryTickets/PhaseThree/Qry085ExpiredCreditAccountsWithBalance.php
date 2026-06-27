<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry085ExpiredCreditAccountsWithBalance extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-085';
    }

    public function title(): string
    {
        return 'Expired credit accounts with balance';
    }

    public function description(): string
    {
        return 'Return expired credit accounts that still have a positive '
            .'balance. Eager load the customer and order by expiration date.';
    }

    public function concepts(): array
    {
        return [
            'whereNotNull',
            'date filter',
            'where',
            'with',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'customer_id',
            'status',
            'balance',
            'expires_at',
            'customer.customer_number',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-085 has not been solved yet.');
    }
}
