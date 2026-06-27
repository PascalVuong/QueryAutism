<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry057ReservationsWithoutParticipants;

class Qry057ReservationsWithoutParticipantsTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_reservations_without_participants(): void
    {
        $results = $this->runPhaseTwoTicket(
            Qry057ReservationsWithoutParticipants::class,
        );

        $this->assertSame([
            'RP-RES-0003',
        ], $results->pluck('reference_number')->all());

        $this->assertTrue(
            $results->every(
                fn ($reservation) => $reservation
                    ->participants()
                    ->doesntExist(),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'reference_number',
            'status',
            'party_size',
        ]);
    }
}