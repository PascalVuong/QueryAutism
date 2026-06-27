<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry069ReservationItemsWithResourceLocation extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-069';
    }

    public function title(): string
    {
        return 'Reservation items with nested resource location';
    }

    public function description(): string
    {
        return 'Return all reservation items ordered by id. Eager load the '
            .'reservation and the nested resource.facility.venue chain.';
    }

    public function concepts(): array
    {
        return [
            'nested eager loading',
            'belongsTo',
            'with',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'reservation_id',
            'resource_id',
            'starts_at',
            'ends_at',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-069 has not been solved yet.');
    }
}