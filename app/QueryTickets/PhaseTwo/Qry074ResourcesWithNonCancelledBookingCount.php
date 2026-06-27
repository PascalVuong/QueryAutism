<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry074ResourcesWithNonCancelledBookingCount extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-074';
    }

    public function title(): string
    {
        return 'Resources with non-cancelled booking count';
    }

    public function description(): string
    {
        return 'Return every resource with the number of reservation items '
            .'whose reservation is not cancelled. Order by the count '
            .'descending and then by resource code.';
    }

    public function concepts(): array
    {
        return [
            'withCount',
            'count alias',
            'whereHas',
            'nested relationship filter',
            'orderByDesc',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'code',
            'name',
            'non_cancelled_booking_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-074 has not been solved yet.');
    }
}
