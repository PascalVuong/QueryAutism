<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry070ReservationsWithCheckedInParticipants;

class Qry070ReservationsWithCheckedInParticipantsTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_checked_in_reservations(): void
    {
        $results = $this->runTicket(
            Qry070ReservationsWithCheckedInParticipants::class,
        );

        $this->assertSame([
            'RP-RES-0001',
        ], $results->pluck('reference_number')->all());

        $reservation = $results->first();

        $this->assertTrue($reservation->relationLoaded('participants'));
        $this->assertCount(4, $reservation->participants);
        $this->assertTrue(
            $reservation->participants->every(
                fn ($participant) => !is_null(
                    $participant->checked_in_at,
                ),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'reference_number',
            'status',
            'starts_at',
            'ends_at',
        ]);
    }
}