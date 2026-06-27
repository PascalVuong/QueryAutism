<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry038MembershipStatusMismatches;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry038MembershipStatusMismatchesTest extends QueryTicketTestCase
{
    public function test_it_returns_membership_status_mismatches(): void
    {
        $results = $this->runTicket(
            Qry038MembershipStatusMismatches::class,
        );

        $this->assertSame(
            ['RP-MEM-0002'],
            $results->pluck('membership_number')->all(),
        );

        foreach ($results as $membership) {
            $this->assertTrue(
                $membership->relationLoaded('effectiveStatusHistory'),
            );

            $this->assertNotNull(
                $membership->effectiveStatusHistory,
            );

            $this->assertNotSame(
                $membership->status,
                $membership->effectiveStatusHistory->to_status,
            );

            $this->assertSame(
                [
                    'id',
                    'membership_id',
                    'to_status',
                    'effective_at',
                ],
                array_keys(
                    $membership
                        ->effectiveStatusHistory
                        ->getAttributes(),
                ),
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'membership_number',
            'status',
        ]);
    }
}
