<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry073VenueReservationRevenue;

class Qry073VenueReservationRevenueTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_revenue_per_venue(): void
    {
        $results = $this->runTicket(
            Qry073VenueReservationRevenue::class,
        );

        $this->assertSame([
            'Green Valley Main Venue',
            'Rotterdam Padel Hall',
            'Serenity Spa',
            'VenueOps Training Centre',
        ], $results->pluck('venue_name')->all());

        $this->assertSame([
            3,
            3,
            1,
            0,
        ], $results->map(
            fn ($row) => (int) $row->reservation_count,
        )->all());

        $this->assertSame([
            280.0,
            160.0,
            90.0,
            0.0,
        ], $results->map(
            fn ($row) => (float) $row->revenue_total,
        )->all());

        $this->assertResultColumns($results, [
            'venue_id',
            'venue_name',
            'reservation_count',
            'revenue_total',
        ]);
    }
}
