<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry059ResourcesNeverBooked;

class Qry059ResourcesNeverBookedTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_resources_never_used_in_items(): void
    {
        $results = $this->runPhaseTwoTicket(
            Qry059ResourcesNeverBooked::class,
        );

        $this->assertSame([
            'BOARDROOM',
            'COURT-3',
        ], $results->pluck('code')->all());

        $this->assertTrue(
            $results->every(
                fn ($resource) => $resource
                    ->reservationItems()
                    ->doesntExist(),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'facility_id',
            'code',
            'name',
            'status',
        ]);
    }
}