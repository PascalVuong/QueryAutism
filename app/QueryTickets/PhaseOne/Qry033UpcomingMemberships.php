<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry033UpcomingMemberships extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-033';
    }

    public function title(): string
    {
        return 'Upcoming memberships';
    }

    public function description(): string
    {
        return 'Return memberships that start after the current moment. '
            .'Show the earliest upcoming membership first and use the '
            .'membership number as the secondary sort.';
    }

    public function concepts(): array
    {
        return [
            'where',
            'date comparison',
            'orderBy',
            'secondary orderBy',
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
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-033 has not been solved yet.');
    }
}
