<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry053BookableActiveResources extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-053';
    }

    public function title(): string
    {
        return 'Bookable active resources';
    }

    public function description(): string
    {
        return 'Return resources that are active and bookable. Order them by code.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'multiple where clauses',
            'boolean where',
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
            'type',
            'status',
            'capacity',
            'is_bookable',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException(
            'QRY-053 has not been solved yet.',
        );
    }
}