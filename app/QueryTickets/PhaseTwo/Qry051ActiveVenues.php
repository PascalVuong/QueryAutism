<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry051ActiveVenues extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-051';
    }

    public function title(): string
    {
        return 'Active venues';
    }

    public function description(): string
    {
        return 'Return active venues ordered by name.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'where',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'name',
            'slug',
            'status',
            'city',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException(
            'QRY-051 has not been solved yet.',
        );
    }
}