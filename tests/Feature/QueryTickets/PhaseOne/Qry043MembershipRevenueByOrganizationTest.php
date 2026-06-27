<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry043MembershipRevenueByOrganization;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry043MembershipRevenueByOrganizationTest extends QueryTicketTestCase
{
    public function test_it_returns_membership_revenue_per_organization(): void
    {
        $results = $this->runTicket(
            Qry043MembershipRevenueByOrganization::class,
        );

        $this->assertSame([
            ['name' => 'Dormant Event Hall', 'revenue' => null],
            ['name' => 'Green Valley Golf Club', 'revenue' => 955.0],
            ['name' => 'Rotterdam Padel Centre', 'revenue' => 885.0],
            ['name' => 'Serenity Wellness', 'revenue' => null],
            ['name' => 'VenueOps Leisure Group', 'revenue' => null],
        ], $results->map(fn ($organization) => [
            'name' => $organization->name,
            'revenue' => is_null(
                $organization->memberships_sum_agreed_price,
            )
                ? null
                : round(
                    (float) $organization->memberships_sum_agreed_price,
                    2,
                ),
        ])->all());

        $this->assertResultColumns($results, [
            'id',
            'name',
            'memberships_sum_agreed_price',
        ]);
    }
}