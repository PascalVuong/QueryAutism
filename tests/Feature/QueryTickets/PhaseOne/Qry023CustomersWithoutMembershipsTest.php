<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry023CustomersWithoutMemberships;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry023CustomersWithoutMembershipsTest extends QueryTicketTestCase
{
    public function test_it_returns_customers_without_memberships(): void
    {
        $results = $this->runTicket(
            Qry023CustomersWithoutMemberships::class,
        );

        $expectedIds = DB::table('customers')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('memberships')
                    ->whereColumn(
                        'memberships.customer_id',
                        'customers.id',
                    );
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
