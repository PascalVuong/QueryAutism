<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry027OrganizationsWithCustomerCount;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry027OrganizationsWithCustomerCountTest extends QueryTicketTestCase
{
    public function test_it_returns_customer_count_for_every_organization(): void
    {
        $results = $this->runTicket(
            Qry027OrganizationsWithCustomerCount::class,
        );

        $expected = DB::table('organizations')
            ->leftJoin(
                'customers',
                'customers.organization_id',
                '=',
                'organizations.id',
            )
            ->select([
                'organizations.id',
                'organizations.name',
            ])
            ->selectRaw('COUNT(customers.id) AS customers_count')
            ->groupBy('organizations.id', 'organizations.name')
            ->orderByDesc('customers_count')
            ->orderBy('organizations.name')
            ->get()
            ->map(fn ($organization) => [
                'id' => $organization->id,
                'customers_count' => (int) $organization->customers_count,
            ])
            ->all();

        $actual = $results
            ->map(fn ($organization) => [
                'id' => $organization->id,
                'customers_count' => (int) $organization->customers_count,
            ])
            ->all();

        $this->assertSame($expected, $actual);

        $this->assertExactColumns($results, [
            'id',
            'name',
            'slug',
            'customers_count',
        ]);
    }
}
