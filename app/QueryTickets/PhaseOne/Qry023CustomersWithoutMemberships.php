<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry023CustomersWithoutMemberships extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-023';
    }

    public function title(): string
    {
        return 'Customers without memberships';
    }

    public function description(): string
    {
        return 'Return customers that do not have any memberships. '
            .'Order them by customer number.';
    }

    public function concepts(): array
    {
        return [
            'doesntHave',
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
        throw new LogicException('QRY-023 has not been solved yet.');
    }
}
