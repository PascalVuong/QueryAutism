<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry026CustomersWithMultipleMemberships extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-026';
    }

    public function title(): string
    {
        return 'Customers with multiple memberships';
    }

    public function description(): string
    {
        return 'Return customers that have at least two memberships. '
            .'Order them by customer number.';
    }

    public function concepts(): array
    {
        return [
            'has with count operator',
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
        throw new LogicException('QRY-026 has not been solved yet.');
    }
}
