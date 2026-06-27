<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry058ReservationsWithParticipantCount;

class Qry058ReservationsWithParticipantCountTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_participant_count_per_reservation(): void
    {
        $results = $this->runPhaseTwoTicket(
            Qry058ReservationsWithParticipantCount::class,
        );

        $this->assertSame([
            'GV-RES-0001' => 4,
            'GV-RES-0002' => 2,
            'GV-RES-0003' => 1,
            'GV-RES-0004' => 5,
            'RP-RES-0001' => 4,
            'RP-RES-0002' => 4,
            'RP-RES-0003' => 0,
            'SW-RES-0001' => 1,
        ], $results->pluck(
            'participants_count',
            'reference_number',
        )->all());

        $this->assertExactColumns($results, [
            'id',
            'reference_number',
            'party_size',
            'participants_count',
        ]);
    }
}