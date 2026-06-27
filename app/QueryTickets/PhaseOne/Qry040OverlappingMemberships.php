<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry040OverlappingMemberships extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-040';
    }

    public function title(): string
    {
        return 'Overlapping memberships';
    }

    public function description(): string
    {
        return 'Return every membership that overlaps another non-deleted '
            .'membership belonging to the same customer. Two periods '
            .'overlap when each starts before the other ends; a null end '
            .'date has no upper limit. Order by customer_id, starts_at and id.';
    }

    public function concepts(): array
    {
        return [
            'correlated subquery',
            'whereExists',
            'whereColumn',
            'null end dates',
            'date overlap',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'customer_id',
            'membership_number',
            'starts_at',
            'ends_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-040 has not been solved yet.');
    }
}
