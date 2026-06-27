<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry046CustomerWithMostMemberships;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry046CustomerWithMostMembershipsTest extends QueryTicketTestCase
{
    public function test_it_returns_the_customer_with_most_memberships(): void
    {
        $results = $this->runTicket(
            Qry046CustomerWithMostMemberships::class,
        );

        $this->assertCount(1, $results);
        $this->assertSame(
            'GV-0005',
            $results->first()->customer_number,
        );
        $this->assertSame(
            2,
            (int) $results->first()->memberships_count,
        );

        $this->assertResultColumns($results, [
            'id',
            'customer_number',
            'first_name',
            'last_name',
            'memberships_count',
        ]);
    }
}