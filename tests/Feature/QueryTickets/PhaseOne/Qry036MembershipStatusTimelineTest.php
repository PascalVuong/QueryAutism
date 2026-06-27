<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\Models\Membership;
use App\QueryTickets\PhaseOne\Qry036MembershipStatusTimeline;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry036MembershipStatusTimelineTest extends QueryTicketTestCase
{
    public function test_it_returns_the_membership_status_timeline(): void
    {
        $results = $this->runTicket(
            Qry036MembershipStatusTimeline::class,
        );

        $membership = Membership::query()
            ->where('membership_number', 'GV-MEM-0001')
            ->firstOrFail();

        $this->assertSame(
            $membership->id,
            $results->pluck('membership_id')->unique()->sole(),
        );

        $this->assertSame(
            ['pending', 'active'],
            $results->pluck('to_status')->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'membership_id',
            'from_status',
            'to_status',
            'effective_at',
        ]);
    }
}
