<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry054MaintenanceResources extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-054';
    }

    public function title(): string
    {
        return 'Resources in maintenance';
    }

    public function description(): string
    {
        return 'Return resources whose current status is maintenance. Order them by code.';
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
            'facility_id',
            'code',
            'name',
            'status',
            'is_bookable',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException(
            'QRY-054 has not been solved yet.',
        );
    }
}