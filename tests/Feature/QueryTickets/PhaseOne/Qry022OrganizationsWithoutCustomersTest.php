<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry022OrganizationsWithoutCustomers;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry022OrganizationsWithoutCustomersTest extends QueryTicketTestCase
{
    public function test_it_returns_organizations_without_customers(): void
    {
        $results = $this->runTicket(
            Qry022OrganizationsWithoutCustomers::class,
        );

        $expectedIds = DB::table('organizations')
            ->whereNotExists(function ($query) {
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
