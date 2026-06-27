<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry031CurrentActiveMemberships;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry031CurrentActiveMembershipsTest extends QueryTicketTestCase
{
    public function test_it_returns_currently_active_memberships(): void
    {
        $results = $this->runTicket(
            Qry031CurrentActiveMemberships::class,
        );

        $expected = DB::table('memberships')
            ->whereNull('deleted_at')
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where(function ($query) {
                $query
                    ->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
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
                    $membership->status === 'active'
                    && $membership->starts_at->lte(now())
                    && (
                        is_null($membership->ends_at)
                        || $membership->ends_at->gt(now())
                    ),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'customer_id',
            'membership_number',
            'status',
            'starts_at',
            'ends_at',
        ]);
    }
}
