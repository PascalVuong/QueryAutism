<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry035CancelledMembershipsWithReason extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-035';
    }

    public function title(): string
    {
        return 'Cancelled memberships with a reason';
    }

    public function description(): string
    {
        return 'Return cancelled memberships with a non-null and non-empty '
            .'cancellation reason. Order by membership number.';
    }

    public function concepts(): array
    {
        return [
            'where',
            'whereNotNull',
            'not equal',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'membership_number',
            'status',
            'cancelled_at',
            'cancellation_reason',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-035 has not been solved yet.');
    }
}
