<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry014CustomersWithUserAccounts;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry014CustomersWithUserAccountsTest extends QueryTicketTestCase
{
    public function test_it_eager_loads_users_for_linked_customers(): void
    {
        $results = $this->runTicket(Qry014CustomersWithUserAccounts::class);

        $expectedIds = DB::table('customers')
            ->whereNotNull('user_id')
            ->orderBy('customer_number')
            ->pluck('id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($customer) => $customer->relationLoaded('user')
                    && !is_null($customer->user),
            ),
        );

        foreach ($results as $customer) {
            $this->assertSame(
                ['id', 'name', 'email', 'status'],
                array_keys($customer->user->getAttributes()),
            );
        }

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
