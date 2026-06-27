<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry053BookableActiveResources;

class Qry053BookableActiveResourcesTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_active_bookable_resources(): void
    {
        $results = $this->runPhaseTwoTicket(
            Qry053BookableActiveResources::class,
        );

        $this->assertSame([
            'BOARDROOM',
            'COURT-1',
            'COURT-2',
            'LOUNGE-TABLE',
            'NORTH',
            'ROOM-1',
            'SIM-1',
            'TABLE-A',
        ], $results->pluck('code')->all());

        $this->assertTrue(
            $results->every(
                fn ($resource) => $resource->status === 'active'
                    && $resource->is_bookable,
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'facility_id',
            'code',
            'name',
            'type',
            'status',
            'capacity',
            'is_bookable',
        ]);
    }
}