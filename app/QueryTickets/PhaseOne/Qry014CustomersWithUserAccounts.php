<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry014CustomersWithUserAccounts extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-014';
    }

    public function title(): string
    {
        return 'Customers with user accounts';
    }

    public function description(): string
    {
        return 'Return customers linked to a user account. Eager load '
            .'each user with only id, name, email and status. Order the '
            .'customers by customer number.';
    }

    public function concepts(): array
    {
        return [
            'whereNotNull',
            'constrained eager loading',
            'with',
            'orderBy',
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
        throw new LogicException('QRY-014 has not been solved yet.');
    }
}
