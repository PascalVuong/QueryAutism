<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry070ReservationsWithCheckedInParticipants extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-070';
    }

    public function title(): string
    {
        return 'Reservations with checked-in participants';
    }

    public function description(): string
    {
        return 'Return reservations with at least one checked-in participant. '
            .'Eager load only checked-in participants and order by reference.';
    }

    public function concepts(): array
    {
        return [
            'withWhereHas',
            'whereNotNull',
            'constrained eager loading',
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
        throw new LogicException('QRY-070 has not been solved yet.');
    }
}