<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry037MembershipsWithLatestStatus;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry037MembershipsWithLatestStatusTest extends QueryTicketTestCase
{
    public function test_it_eager_loads_the_latest_status_history(): void
    {
        $results = $this->runTicket(
            Qry037MembershipsWithLatestStatus::class,
        );

        $expectedNumbers = DB::table('memberships')
            ->whereNull('deleted_at')
            ->orderBy('membership_number')
            ->pluck('membership_number')
            ->all();

        $this->assertSame(
            $expectedNumbers,
            $results->pluck('membership_number')->all(),
        );

        foreach ($results as $membership) {
            $this->assertTrue(
                $membership->relationLoaded('latestStatusHistory'),
            );

            $expectedStatus = DB::table('membership_status_histories')
                ->where('membership_id', $membership->id)
                ->orderByDesc('id')
                ->value('to_status');

            $this->assertSame(
                $expectedStatus,
                $membership->latestStatusHistory?->to_status,
            );

            if ($membership->latestStatusHistory !== null) {
                $this->assertSame(
                    [
                        'id',
                        'membership_id',
                        'to_status',
                        'effective_at',
                    ],
                    array_keys(
                        $membership
                            ->latestStatusHistory
                            ->getAttributes(),
                    ),
                );
            }
        }

        $this->assertExactColumns($results, [
            'id',
            'membership_number',
            'status',
        ]);
    }
}
