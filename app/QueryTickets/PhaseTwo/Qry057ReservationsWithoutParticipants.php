<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry057ReservationsWithoutParticipants extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-057';
    }

    public function title(): string
    {
        return 'Reservations without participants';
    }

    public function description(): string
    {
        return 'Return reservations that do not have any participants. Order them by reference number.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'doesntHave',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'reference_number',
            'status',
            'party_size',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException(
            'QRY-057 has not been solved yet.',
        );
    }
}