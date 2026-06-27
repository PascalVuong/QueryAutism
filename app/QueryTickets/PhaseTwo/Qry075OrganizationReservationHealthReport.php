<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry075OrganizationReservationHealthReport extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-075';
    }

    public function title(): string
    {
        return 'Organization reservation health report';
    }

    public function description(): string
    {
        return 'Return every organization with its total reservation count, '
            .'non-cancelled upcoming count, cancelled count and revenue '
            .'excluding cancelled reservations. Include organizations without '
            .'reservations and order by organization name.';
    }

    public function concepts(): array
    {
        return [
            'leftJoin',
            'conditional aggregates',
            'CASE WHEN',
            'COUNT',
            'SUM',
            'COALESCE',
            'groupBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'organization_id',
            'organization_name',
            'reservation_count',
            'upcoming_count',
            'cancelled_count',
            'revenue_total',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-075 has not been solved yet.');
    }
}
