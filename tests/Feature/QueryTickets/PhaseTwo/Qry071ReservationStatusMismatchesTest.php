<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry071ReservationStatusMismatches;

class Qry071ReservationStatusMismatchesTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_reservation_status_mismatches(): void
    {
        $results = $this->runTicket(
            Qry071ReservationStatusMismatches::class,
        );

        $this->assertSame([
            'RP-RES-0003',
        ], $results->pluck('reference_number')->all());

        $reservation = $results->first();

        $this->assertTrue(
            $reservation->relationLoaded('effectiveStatusHistory'),
        );
        $this->assertSame('pending', $reservation->status);
        $this->assertSame(
            'confirmed',
            $reservation->effectiveStatusHistory->to_status,
        );

        $this->assertExactColumns($results, [
            'id',
            'reference_number',
            'status',
        ]);
    }
}
