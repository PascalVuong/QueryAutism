<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry029PlansWithActiveMembershipCount;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry029PlansWithActiveMembershipCountTest extends QueryTicketTestCase
{
    public function test_it_counts_only_active_memberships_per_plan(): void
    {
        $results = $this->runTicket(
            Qry029PlansWithActiveMembershipCount::class,
        );

        $expected = DB::table('membership_plans')
            ->leftJoin('memberships', function ($join) {
                $join->on(
                    'memberships.membership_plan_id',
                    '=',
                    'membership_plans.id',
                )->where('memberships.status', 'active');
            })
            ->select([
                'membership_plans.id',
                'membership_plans.name',
            ])
            ->selectRaw(
                'COUNT(memberships.id) AS active_memberships_count',
            )
            ->groupBy('membership_plans.id', 'membership_plans.name')
            ->orderByDesc('active_memberships_count')
            ->orderBy('membership_plans.name')
            ->get()
            ->map(fn ($plan) => [
                'id' => $plan->id,
                'active_memberships_count' => (int) $plan
                    ->active_memberships_count,
            ])
            ->all();

        $actual = $results
            ->map(fn ($plan) => [
                'id' => $plan->id,
                'active_memberships_count' => (int) $plan
                    ->active_memberships_count,
            ])
            ->all();

        $this->assertSame($expected, $actual);

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'code',
            'name',
            'status',
            'active_memberships_count',
        ]);
    }
}
