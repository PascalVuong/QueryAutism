<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry032ExpiredMembershipsByEndDate;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry032ExpiredMembershipsByEndDateTest extends QueryTicketTestCase
{
    public function test_it_returns_memberships_ended_before_now(): void
    {
        $results = $this->runTicket(
            Qry032ExpiredMembershipsByEndDate::class,
        );

        $expected = DB::table('memberships')
            ->whereNull('deleted_at')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->orderByDesc('ends_at')
            ->orderBy('membership_number')
            ->pluck('membership_number')
            ->all();

        $this->assertSame(
            $expected,
            $results->pluck('membership_number')->all(),
        );

        $this->assertTrue(
            $results->every(
                fn ($membership) =>
                    $membership->ends_at !== null
                    && $membership->ends_at->lt(now()),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'customer_id',
            'membership_number',
            'status',
            'ends_at',
        ]);
    }
}
