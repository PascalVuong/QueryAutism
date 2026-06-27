<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry041MembershipCountByStatus extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-041';
    }

    public function title(): string
    {
        return 'Membership count per status';
    }

    public function description(): string
    {
        return 'Group memberships by status and return the number of '
            .'memberships per status. Order the rows by status.';
    }

    public function concepts(): array
    {
        return [
            'selectRaw',
            'count',
            'groupBy',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'status',
            'membership_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-041 has not been solved yet.');
    }
}