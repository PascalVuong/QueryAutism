<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry058ReservationsWithParticipantCount extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-058';
    }

    public function title(): string
    {
        return 'Reservations with participant count';
    }

    public function description(): string
    {
        return 'Return every reservation with participants_count. Order them by reference number.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'withCount',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'reference_number',
            'party_size',
            'participants_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException(
            'QRY-058 has not been solved yet.',
        );
    }
}