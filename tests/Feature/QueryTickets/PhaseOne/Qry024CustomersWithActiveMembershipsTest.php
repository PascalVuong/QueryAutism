<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry024CustomersWithActiveMemberships;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry024CustomersWithActiveMembershipsTest extends QueryTicketTestCase
{
    public function test_it_returns_customers_with_active_memberships(): void
    {
        $results = $this->runTicket(
            Qry024CustomersWithActiveMemberships::class,
        );

        $expectedIds = DB::table('customers')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('memberships')
                    ->whereColumn(
                        'memberships.customer_id',
                        'customers.id',
                    )
                    ->where('memberships.status', 'active');
            })
            ->orderBy('customer_number')
            ->pluck('id')
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
