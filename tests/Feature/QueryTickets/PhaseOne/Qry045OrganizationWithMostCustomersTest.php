<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry045OrganizationWithMostCustomers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry045OrganizationWithMostCustomersTest extends QueryTicketTestCase
{
    public function test_it_returns_the_organization_with_most_customers(): void
    {
        $results = $this->runTicket(
            Qry045OrganizationWithMostCustomers::class,
        );

        $this->assertCount(1, $results);
        $this->assertSame(
            'Green Valley Golf Club',
            $results->first()->name,
        );
        $this->assertSame(7, (int) $results->first()->customers_count);

        $this->assertResultColumns($results, [
            'id',
            'name',
            'customers_count',
        ]);
    }
}