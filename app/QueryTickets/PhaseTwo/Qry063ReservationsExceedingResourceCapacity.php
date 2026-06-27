<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry063ReservationsExceedingResourceCapacity extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-063';
    }

    public function title(): string
    {
        return 'Reservations exceeding resource capacity';
    }

    public function description(): string
    {
        return 'Return reservations whose party size is greater than the '
            .'capacity of at least one attached resource. Order by reference.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'whereColumn',
            'relationship comparison',
            'distinct',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'reference_number',
            'party_size',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-063 has not been solved yet.');
    }
}