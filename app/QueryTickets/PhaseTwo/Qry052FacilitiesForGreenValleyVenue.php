<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry052FacilitiesForGreenValleyVenue extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-052';
    }

    public function title(): string
    {
        return 'Facilities for one venue';
    }

    public function description(): string
    {
        return 'Return active facilities belonging to the venue with slug green-valley-main-venue. Order them by code.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'where',
            'whereHas',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'venue_id',
            'code',
            'name',
            'type',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException(
            'QRY-052 has not been solved yet.',
        );
    }
}