<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry025CustomersWithoutActiveMemberships extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-025';
    }

    public function title(): string
    {
        return 'Customers without active memberships';
    }

    public function description(): string
    {
        return 'Return customers that do not have a membership whose status '
            .'is active. Order them by customer number.';
    }

    public function concepts(): array
    {
        return [
            'whereDoesntHave',
            'relationship conditions',
            'select',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'customer_number',
            'first_name',
            'last_name',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-025 has not been solved yet.');
    }
}
