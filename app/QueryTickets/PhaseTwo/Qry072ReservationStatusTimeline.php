<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry072ReservationStatusTimeline extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-072';
    }

    public function title(): string
    {
        return 'Reservation status timeline';
    }

    public function description(): string
    {
        return 'Return the status history for reservation GV-RES-0001. '
            .'Order changes by effective_at and then by id.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'relationship filtering',
            'orderBy',
            'history table',
            'select',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'reservation_id',
            'from_status',
            'to_status',
            'effective_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-072 has not been solved yet.');
    }
}
