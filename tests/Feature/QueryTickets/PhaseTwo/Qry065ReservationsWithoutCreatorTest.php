<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry065ReservationsWithoutCreator;

class Qry065ReservationsWithoutCreatorTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_reservations_without_creator(): void
    {
        $results = $this->runTicket(
            Qry065ReservationsWithoutCreator::class,
        );

        $this->assertSame([
            'SW-RES-0001',
        ], $results->pluck('reference_number')->all());

        $this->assertTrue(
            $results->every(
                fn ($reservation) => is_null(
                    $reservation->created_by_user_id,
                ),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'reference_number',
            'status',
            'created_by_user_id',
        ]);
    }
}