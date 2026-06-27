<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry071ReservationStatusMismatches extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-071';
    }

    public function title(): string
    {
        return 'Reservation status mismatches';
    }

    public function description(): string
    {
        return 'Return reservations whose stored status differs from the '
            .'latest effective status history. Eager load that history and '
            .'order by reference number.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'whereColumn',
            'effectiveStatusHistory',
            'constrained eager loading',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'reference_number',
            'status',
            'effectiveStatusHistory.to_status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-071 has not been solved yet.');
    }
}
