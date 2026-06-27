<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry028CustomersWithMembershipCount;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry028CustomersWithMembershipCountTest extends QueryTicketTestCase
{
    public function test_it_returns_membership_count_for_every_customer(): void
    {
        $results = $this->runTicket(
            Qry028CustomersWithMembershipCount::class,
        );

        $expected = DB::table('customers')
            ->leftJoin(
                'memberships',
                'memberships.customer_id',
                '=',
                'customers.id',
            )
            ->select([
                'customers.id',
                'customers.customer_number',
            ])
            ->selectRaw('COUNT(memberships.id) AS memberships_count')
            ->groupBy('customers.id', 'customers.customer_number')
            ->orderByDesc('memberships_count')
            ->orderBy('customers.customer_number')
            ->get()
            ->map(fn ($customer) => [
                'id' => $customer->id,
                'memberships_count' => (int) $customer->memberships_count,
            ])
            ->all();

        $actual = $results
            ->map(fn ($customer) => [
                'id' => $customer->id,
                'memberships_count' => (int) $customer->memberships_count,
            ])
            ->all();

        $this->assertSame($expected, $actual);

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'customer_number',
            'first_name',
            'last_name',
            'memberships_count',
        ]);
    }
}
