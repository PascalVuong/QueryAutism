<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry026CustomersWithMultipleMemberships;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry026CustomersWithMultipleMembershipsTest extends QueryTicketTestCase
{
    public function test_it_returns_customers_with_at_least_two_memberships(): void
    {
        $results = $this->runTicket(
            Qry026CustomersWithMultipleMemberships::class,
        );

        $expectedIds = DB::table('customers')
            ->join(
                'memberships',
                'memberships.customer_id',
                '=',
                'customers.id',
            )
            ->groupBy('customers.id', 'customers.customer_number')
            ->havingRaw('COUNT(memberships.id) >= 2')
            ->orderBy('customers.customer_number')
            ->pluck('customers.id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'customer_number',
            'first_name',
            'last_name',
            'status',
        ]);
    }
}
