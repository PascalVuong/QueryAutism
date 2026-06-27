<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry030HighRiskCustomers;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry030HighRiskCustomersTest extends QueryTicketTestCase
{
    public function test_it_returns_customers_with_high_risk_profiles(): void
    {
        $results = $this->runTicket(Qry030HighRiskCustomers::class);

        $expectedIds = DB::table('customers')
            ->join(
                'customer_profiles',
                'customer_profiles.customer_id',
                '=',
                'customers.id',
            )
            ->where('customer_profiles.risk_score', '>=', 70)
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
