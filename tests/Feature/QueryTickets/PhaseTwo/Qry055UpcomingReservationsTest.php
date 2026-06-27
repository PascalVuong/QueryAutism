<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry055UpcomingReservations;

class Qry055UpcomingReservationsTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_non_cancelled_future_reservations(): void
    {
        $results = $this->runPhaseTwoTicket(
            Qry055UpcomingReservations::class,
        );

        $this->assertSame([
            'GV-RES-0001',
            'GV-RES-0002',
            'RP-RES-0002',
            'GV-RES-0004',
            'RP-RES-0003',
        ], $results->pluck('reference_number')->all());

        $this->assertTrue(
            $results->every(
                fn ($reservation) => $reservation->starts_at->isFuture()
                    && $reservation->status !== 'cancelled',
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'venue_id',
            'customer_id',
            'reference_number',
            'status',
            'starts_at',
            'ends_at',
            'party_size',
            'total',
        ]);
    }
}