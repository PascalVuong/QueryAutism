<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry061OverlappingResourceReservations extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-061';
    }

    public function title(): string
    {
        return 'Overlapping reservations on the same resource';
    }

    public function description(): string
    {
        return 'Return non-cancelled reservations whose reservation item '
            .'overlaps an item from another reservation on the same resource. '
            .'Return each reservation once and order by reference number.';
    }

    public function concepts(): array
    {
        return [
            'whereExists',
            'correlated subquery',
            'whereColumn',
            'date overlap',
            'distinct',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'reference_number',
            'status',
            'starts_at',
            'ends_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-061 has not been solved yet.');
    }
}