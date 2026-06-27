<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry041MembershipCountByStatus;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry041MembershipCountByStatusTest extends QueryTicketTestCase
{
    public function test_it_counts_memberships_per_status(): void
    {
        $results = $this->runTicket(
            Qry041MembershipCountByStatus::class,
        );

        $this->assertSame([
            ['status' => 'active', 'membership_count' => 5],
            ['status' => 'cancelled', 'membership_count' => 1],
            ['status' => 'expired', 'membership_count' => 1],
            ['status' => 'paused', 'membership_count' => 1],
        ], $results->map(fn ($row) => [
            'status' => $row->status,
            'membership_count' => (int) $row->membership_count,
        ])->all());

        $this->assertResultColumns($results, [
            'status',
            'membership_count',
        ]);
    }
}