<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry024CustomersWithActiveMemberships extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-024';
    }

    public function title(): string
    {
        return 'Customers with active memberships';
    }

    public function description(): string
    {
        return 'Return customers that have at least one membership whose '
            .'status is active. Order them by customer number.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
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
        throw new LogicException('QRY-024 has not been solved yet.');
    }
}
