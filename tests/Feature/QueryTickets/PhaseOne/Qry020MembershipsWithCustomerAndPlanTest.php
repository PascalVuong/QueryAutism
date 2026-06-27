<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry020MembershipsWithCustomerAndPlan;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry020MembershipsWithCustomerAndPlanTest extends QueryTicketTestCase
{
    public function test_it_eager_loads_customer_and_plan(): void
    {
        $results = $this->runTicket(
            Qry020MembershipsWithCustomerAndPlan::class,
        );

        $expectedIds = DB::table('memberships')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($membership) => $membership->relationLoaded('customer')
                    && $membership->relationLoaded('plan')
                    && !is_null($membership->customer)
                    && !is_null($membership->plan),
            ),
        );

        foreach ($results as $membership) {
            $this->assertSame(
                ['id', 'customer_number', 'first_name', 'last_name'],
                array_keys($membership->customer->getAttributes()),
            );
            $this->assertSame(
                ['id', 'code', 'name', 'price', 'status'],
                array_keys($membership->plan->getAttributes()),
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'customer_id',
            'membership_plan_id',
            'status',
            'starts_at',
            'ends_at',
            'agreed_price',
        ]);
    }
}
