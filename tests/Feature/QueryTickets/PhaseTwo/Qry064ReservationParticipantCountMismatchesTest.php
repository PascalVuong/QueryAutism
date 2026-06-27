<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry064ReservationParticipantCountMismatches;

class Qry064ReservationParticipantCountMismatchesTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_participant_count_mismatches(): void
    {
        $results = $this->runTicket(
            Qry064ReservationParticipantCountMismatches::class,
        );

        $this->assertSame([
            'GV-RES-0003',
            'RP-RES-0003',
        ], $results->pluck('reference_number')->all());

        $this->assertSame([1, 0], $results
            ->pluck('participants_count')
            ->all());

        $this->assertExactColumns($results, [
            'id',
            'reference_number',
            'party_size',
            'participants_count',
        ]);
    }
}