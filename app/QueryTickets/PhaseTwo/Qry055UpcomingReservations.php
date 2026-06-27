<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry055UpcomingReservations extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-055';
    }

    public function title(): string
    {
        return 'Upcoming reservations';
    }

    public function description(): string
    {
        return 'Return future reservations that are not cancelled. Show the earliest reservation first.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'where',
            'whereNot',
            'now',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'venue_id',
            'customer_id',
            'reference_number',
            'status',
            'starts_at',
            'ends_at',
            'party_size',
            'total',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException(
            'QRY-055 has not been solved yet.',
        );
    }
}