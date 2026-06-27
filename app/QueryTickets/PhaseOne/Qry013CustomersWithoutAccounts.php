<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry013CustomersWithoutAccounts extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-013';
    }

    public function title(): string
    {
        return 'Customers without user accounts';
    }

    public function description(): string
    {
        return 'Return customers that are not linked to a user account. '
            .'Order them by customer number.';
    }

    public function concepts(): array
    {
        return [
            'whereNull',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'user_id',
            'customer_number',
            'first_name',
            'last_name',
            'email',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-013 has not been solved yet.');
    }
}
