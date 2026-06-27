<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\Models\Membership;
use App\QueryTickets\PhaseOne\Qry050OrganizationHealthReport;
use Carbon\Carbon;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry050OrganizationHealthReportTest extends QueryTicketTestCase
{
    public function test_it_returns_the_organization_health_report(): void
    {
        $results = $this->runTicket(
            Qry050OrganizationHealthReport::class,
        );

        $this->assertSame([
            [
                'name' => 'Dormant Event Hall',
                'customer_count' => 0,
                'active_membership_count' => 0,
                'expired_membership_count' => 0,
                'membership_revenue' => 0.0,
                'blocked_customer_count' => 0,
            ],
            [
                'name' => 'Green Valley Golf Club',
                'customer_count' => 7,
                'active_membership_count' => 3,
                'expired_membership_count' => 1,
                'membership_revenue' => 955.0,
                'blocked_customer_count' => 1,
            ],
            [
                'name' => 'Rotterdam Padel Centre',
                'customer_count' => 4,
                'active_membership_count' => 2,
                'expired_membership_count' => 0,
                'membership_revenue' => 885.0,
                'blocked_customer_count' => 0,
            ],
            [
                'name' => 'Serenity Wellness',
                'customer_count' => 0,
                'active_membership_count' => 0,
                'expired_membership_count' => 0,
                'membership_revenue' => 0.0,
                'blocked_customer_count' => 0,
            ],
            [
                'name' => 'VenueOps Leisure Group',
                'customer_count' => 0,
                'active_membership_count' => 0,
                'expired_membership_count' => 0,
                'membership_revenue' => 0.0,
                'blocked_customer_count' => 0,
            ],
        ], $results->map(fn ($row) => [
            'name' => $row->name,
            'customer_count' => (int) $row->customer_count,
            'active_membership_count' => (int) $row->active_membership_count,
            'expired_membership_count' => (int) $row->expired_membership_count,
            'membership_revenue' => round(
                (float) $row->membership_revenue,
                2,
            ),
            'blocked_customer_count' => (int) $row->blocked_customer_count,
        ])->all());

        foreach ($results as $row) {
            $expectedLatest = Membership::query()
                ->where('organization_id', $row->id)
                ->max('updated_at');

            $actualLatest = $row->latest_membership_updated_at;

            if (is_null($expectedLatest)) {
                $this->assertNull($actualLatest);

                continue;
            }

            $this->assertTrue(
                Carbon::parse($expectedLatest)->equalTo(
                    Carbon::parse($actualLatest),
                ),
            );
        }

        $this->assertResultColumns($results, [
            'id',
            'name',
            'customer_count',
            'active_membership_count',
            'expired_membership_count',
            'membership_revenue',
            'blocked_customer_count',
            'latest_membership_updated_at',
        ]);
    }
}