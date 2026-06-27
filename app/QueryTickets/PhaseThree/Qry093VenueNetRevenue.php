<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry093VenueNetRevenue extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-093';
    }

    public function title(): string
    {
        return 'Venue net revenue';
    }

    public function description(): string
    {
        return 'Return every venue with successful payment count, gross '
            .'amount, refunded amount and net revenue through reservations. '
            .'Include venues without payments and order by venue name.';
    }

    public function concepts(): array
    {
        return [
            'multiple left joins',
            'conditional aggregates',
            'SUM',
            'COUNT',
            'GROUP BY',
            'multi-table report',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'venue_name',
            'successful_payment_count',
            'gross_amount',
            'refund_amount',
            'net_revenue',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-093 has not been solved yet.');
    }
}
