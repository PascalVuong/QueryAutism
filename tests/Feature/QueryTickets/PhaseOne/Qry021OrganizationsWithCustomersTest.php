<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry021OrganizationsWithCustomers;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry021OrganizationsWithCustomersTest extends QueryTicketTestCase
{
    public function test_it_returns_organizations_with_customers(): void
    {
        $results = $this->runTicket(
            Qry021OrganizationsWithCustomers::class,
        );

        $expectedIds = DB::table('organizations')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('customers')
                    ->whereColumn(
                        'customers.organization_id',
                        'organizations.id',
                    );
            })
            ->orderBy('name')
            ->pluck('id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);

        $this->assertExactColumns($results, [
            'id',
            'name',
            'slug',
            'status',
        ]);
    }
}
