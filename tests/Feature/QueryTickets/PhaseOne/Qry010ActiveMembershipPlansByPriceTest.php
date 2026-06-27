<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry010ActiveMembershipPlansByPrice;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry010ActiveMembershipPlansByPriceTest extends QueryTicketTestCase
{
    public function test_it_returns_active_plans_from_low_to_high_price(): void
    {
        $results = $this->runTicket(
            Qry010ActiveMembershipPlansByPrice::class,
        );

        $this->assertSame([
            'GUEST',
            'PADEL',
            'STANDARD',
            'PREMIUM',
            'CORPORATE',
        ], $results->pluck('code')->all());

        $this->assertSame(
            ['active'],
            $results->pluck('status')->unique()->values()->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'code',
            'name',
            'status',
            'price',
            'currency',
        ]);
    }
}