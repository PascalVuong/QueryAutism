<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry013CustomersWithoutAccounts;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry013CustomersWithoutAccountsTest extends QueryTicketTestCase
{
    public function test_it_returns_customers_without_user_accounts(): void
    {
        $results = $this->runTicket(Qry013CustomersWithoutAccounts::class);

        $expectedIds = DB::table('customers')
            ->whereNull('user_id')
            ->orderBy('customer_number')
            ->pluck('id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(fn ($customer) => is_null($customer->user_id)),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'user_id',
            'customer_number',
            'first_name',
            'last_name',
            'email',
            'status',
        ]);
    }
}
