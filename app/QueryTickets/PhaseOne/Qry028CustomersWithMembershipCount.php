<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry028CustomersWithMembershipCount extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-028';
    }

    public function title(): string
    {
        return 'Customers with membership count';
    }

    public function description(): string
    {
        return 'Return every customer with memberships_count. Order by '
            .'memberships_count descending and then by customer number.';
    }

    public function concepts(): array
    {
        return [
            'withCount',
            'select',
            'orderByDesc',
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
            'memberships_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-028 has not been solved yet.');
    }
}
