<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry062ReservationsDuringAvailabilityBlocks extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-062';
    }

    public function title(): string
    {
        return 'Reservations during resource availability blocks';
    }

    public function description(): string
    {
        return 'Return reservations whose item overlaps an availability block '
            .'for the same resource. Order by reference number.';
    }

    public function concepts(): array
    {
        return [
            'whereExists',
            'correlated subquery',
            'whereColumn',
            'date overlap',
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
        throw new LogicException('QRY-062 has not been solved yet.');
    }
}