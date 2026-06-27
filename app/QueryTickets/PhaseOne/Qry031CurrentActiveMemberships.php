<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry031CurrentActiveMemberships extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-031';
    }

    public function title(): string
    {
        return 'Currently active memberships';
    }

    public function description(): string
    {
        return 'Return active memberships that have already started and '
            .'have not ended yet. A null end date means no fixed end date. '
            .'Order by membership number.';
    }

    public function concepts(): array
    {
        return [
            'where',
            'grouped conditions',
            'whereNull',
            'orWhere',
            'date comparisons',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'customer_id',
            'membership_number',
            'status',
            'starts_at',
            'ends_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-031 has not been solved yet.');
    }
}
