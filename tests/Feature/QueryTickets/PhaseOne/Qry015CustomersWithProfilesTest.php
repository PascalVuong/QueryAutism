<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry015CustomersWithProfiles;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry015CustomersWithProfilesTest extends QueryTicketTestCase
{
    public function test_it_returns_customers_with_eager_loaded_profiles(): void
    {
        $results = $this->runTicket(Qry015CustomersWithProfiles::class);

        $expectedIds = DB::table('customers')
            ->join(
                'customer_profiles',
                'customer_profiles.customer_id',
                '=',
                'customers.id',
            )
            ->orderBy('customers.customer_number')
            ->pluck('customers.id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($customer) => $customer->relationLoaded('profile')
                    && !is_null($customer->profile),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'customer_number',
            'first_name',
            'last_name',
            'email',
            'status',
        ]);
    }
}
