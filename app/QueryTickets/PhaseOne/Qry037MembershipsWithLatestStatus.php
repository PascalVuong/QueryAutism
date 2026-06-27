<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry037MembershipsWithLatestStatus extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-037';
    }

    public function title(): string
    {
        return 'Memberships with latest status history';
    }

    public function description(): string
    {
        return 'Return every membership with the latestStatusHistory '
            .'relationship eager loaded. Select only the requested columns '
            .'for both models and order by membership number.';
    }

    public function concepts(): array
    {
        return [
            'with',
            'constrained eager loading',
            'latestOfMany',
            'select',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'membership_number',
            'status',
            'latestStatusHistory.to_status',
            'latestStatusHistory.effective_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-037 has not been solved yet.');
    }
}
