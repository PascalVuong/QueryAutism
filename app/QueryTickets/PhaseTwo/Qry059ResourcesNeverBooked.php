<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry059ResourcesNeverBooked extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-059';
    }

    public function title(): string
    {
        return 'Resources never booked';
    }

    public function description(): string
    {
        return 'Return resources that have never been used in a reservation item. Order them by code.';
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
            'facility_id',
            'code',
            'name',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException(
            'QRY-059 has not been solved yet.',
        );
    }
}