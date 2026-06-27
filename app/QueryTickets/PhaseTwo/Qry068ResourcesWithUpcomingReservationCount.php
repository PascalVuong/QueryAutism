<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry068ResourcesWithUpcomingReservationCount extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-068';
    }

    public function title(): string
    {
        return 'Resources with upcoming reservation count';
    }

    public function description(): string
    {
        return 'Return every resource with the number of future, '
            .'non-cancelled reservation items as '
            .'upcoming_reservation_items_count. Sort by that count descending '
            .'and then by resource name.';
    }

    public function concepts(): array
    {
        return [
            'withCount alias',
            'constrained relationship count',
            'whereHas',
            'date filter',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'code',
            'upcoming_reservation_items_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-068 has not been solved yet.');
    }
}