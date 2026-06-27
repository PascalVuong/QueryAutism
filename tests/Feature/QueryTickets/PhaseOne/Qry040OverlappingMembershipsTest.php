<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry040OverlappingMemberships;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry040OverlappingMembershipsTest extends QueryTicketTestCase
{
    public function test_it_returns_memberships_with_overlapping_periods(): void
    {
        $results = $this->runTicket(
            Qry040OverlappingMemberships::class,
        );

        $this->assertSame(
            [
                'GV-MEM-0005',
                'GV-MEM-0006',
            ],
            $results->pluck('membership_number')->all(),
        );

        $this->assertSame(
            1,
            $results->pluck('customer_id')->unique()->count(),
        );

        $this->assertExactColumns($results, [
            'id',
            'customer_id',
            'membership_number',
            'starts_at',
            'ends_at',
        ]);
    }
}
