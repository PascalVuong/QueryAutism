<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry064ReservationParticipantCountMismatches extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-064';
    }

    public function title(): string
    {
        return 'Reservation participant count mismatches';
    }

    public function description(): string
    {
        return 'Return reservations where party_size differs from the number '
            .'of participants. Include participants_count and order by '
            .'reference number.';
    }

    public function concepts(): array
    {
        return [
            'withCount',
            'whereColumn',
            'computed relationship count',
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
        throw new LogicException('QRY-064 has not been solved yet.');
    }
}