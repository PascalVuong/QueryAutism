<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry067VenuesWithReservationCount extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-067';
    }

    public function title(): string
    {
        return 'Venues with reservation count';
    }

    public function description(): string
    {
        return 'Return every venue with reservations_count. Sort by the count '
            .'descending and then by venue name.';
    }

    public function concepts(): array
    {
        return ['withCount', 'orderByDesc', 'secondary orderBy'];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
            'reservations_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-067 has not been solved yet.');
    }
}