<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry073VenueReservationRevenue extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-073';
    }

    public function title(): string
    {
        return 'Venue reservation revenue';
    }

    public function description(): string
    {
        return 'Return every venue with its non-cancelled reservation count '
            .'and revenue total. Include venues without reservations. Order '
            .'by revenue descending and venue name ascending.';
    }

    public function concepts(): array
    {
        return [
            'leftJoin',
            'conditional aggregate',
            'COUNT',
            'SUM',
            'COALESCE',
            'groupBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'venue_id',
            'venue_name',
            'reservation_count',
            'revenue_total',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-073 has not been solved yet.');
    }
}
