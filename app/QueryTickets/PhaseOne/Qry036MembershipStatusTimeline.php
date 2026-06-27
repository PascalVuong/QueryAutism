<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry036MembershipStatusTimeline extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-036';
    }

    public function title(): string
    {
        return 'Membership status timeline';
    }

    public function description(): string
    {
        return 'Find membership GV-MEM-0001 and return its status history '
            .'through the statusHistories relationship. Order by '
            .'effective_at and then by id.';
    }

    public function concepts(): array
    {
        return [
            'find a parent record',
            'hasMany relationship query',
            'orderBy',
            'secondary orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'membership_id',
            'from_status',
            'to_status',
            'effective_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-036 has not been solved yet.');
    }
}
