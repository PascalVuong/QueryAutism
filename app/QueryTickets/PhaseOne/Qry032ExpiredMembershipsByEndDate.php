<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry032ExpiredMembershipsByEndDate extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-032';
    }

    public function title(): string
    {
        return 'Memberships expired by end date';
    }

    public function description(): string
    {
        return 'Return memberships whose end date is before the current '
            .'moment, regardless of their stored status. Show the most '
            .'recently ended membership first.';
    }

    public function concepts(): array
    {
        return [
            'whereNotNull',
            'date comparison',
            'orderByDesc',
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
            'ends_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-032 has not been solved yet.');
    }
}
