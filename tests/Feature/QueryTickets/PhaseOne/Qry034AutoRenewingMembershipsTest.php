<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry034AutoRenewingMemberships;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry034AutoRenewingMembershipsTest extends QueryTicketTestCase
{
    public function test_it_returns_active_auto_renewing_memberships(): void
    {
        $results = $this->runTicket(
            Qry034AutoRenewingMemberships::class,
        );

        $expected = DB::table('memberships')
            ->whereNull('deleted_at')
            ->where('status', 'active')
            ->where('auto_renew', true)
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
                    && $membership->auto_renew,
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'membership_number',
            'status',
            'auto_renew',
            'ends_at',
        ]);
    }
}
