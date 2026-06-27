<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry054MaintenanceResources;

class Qry054MaintenanceResourcesTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_resources_in_maintenance(): void
    {
        $results = $this->runPhaseTwoTicket(
            Qry054MaintenanceResources::class,
        );

        $this->assertSame([
            'COURT-3',
        ], $results->pluck('code')->all());

        $this->assertSame(
            ['maintenance'],
            $results->pluck('status')->unique()->values()->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'facility_id',
            'code',
            'name',
            'status',
            'is_bookable',
        ]);
    }
}