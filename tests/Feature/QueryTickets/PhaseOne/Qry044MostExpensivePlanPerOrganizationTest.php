<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry044MostExpensivePlanPerOrganization;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry044MostExpensivePlanPerOrganizationTest extends QueryTicketTestCase
{
    public function test_it_returns_the_most_expensive_plan_per_organization(): void
    {
        $results = $this->runTicket(
            Qry044MostExpensivePlanPerOrganization::class,
        );

        $this->assertSame([
            ['code' => 'PREMIUM', 'price' => 450.0],
            ['code' => 'CORPORATE', 'price' => 900.0],
            ['code' => 'WELLNESS', 'price' => 75.0],
        ], $results->map(fn ($plan) => [
            'code' => $plan->code,
            'price' => round((float) $plan->price, 2),
        ])->all());

        $this->assertResultColumns($results, [
            'id',
            'organization_id',
            'code',
            'name',
            'price',
        ]);
    }
}