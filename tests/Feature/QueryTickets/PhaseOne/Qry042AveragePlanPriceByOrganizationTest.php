<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry042AveragePlanPriceByOrganization;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry042AveragePlanPriceByOrganizationTest extends QueryTicketTestCase
{
    public function test_it_returns_average_plan_price_per_organization(): void
    {
        $results = $this->runTicket(
            Qry042AveragePlanPriceByOrganization::class,
        );

        $this->assertSame([
            ['name' => 'Dormant Event Hall', 'average_price' => null],
            ['name' => 'Green Valley Golf Club', 'average_price' => 198.75],
            ['name' => 'Rotterdam Padel Centre', 'average_price' => 467.5],
            ['name' => 'Serenity Wellness', 'average_price' => 75.0],
            ['name' => 'VenueOps Leisure Group', 'average_price' => null],
        ], $results->map(fn ($organization) => [
            'name' => $organization->name,
            'average_price' => is_null(
                $organization->membership_plans_avg_price,
            )
                ? null
                : round(
                    (float) $organization->membership_plans_avg_price,
                    2,
                ),
        ])->all());

        $this->assertResultColumns($results, [
            'id',
            'name',
            'membership_plans_avg_price',
        ]);
    }
}