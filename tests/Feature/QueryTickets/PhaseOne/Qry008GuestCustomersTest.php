<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry008GuestCustomers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry008GuestCustomersTest extends QueryTicketTestCase
{
    public function test_it_returns_guest_customers(): void
    {
        $results = $this->runTicket(Qry008GuestCustomers::class);

        $this->assertSame([
            'GV-0002',
            'GV-0003',
            'GV-0005',
            'GV-0006',
            'GV-0007',
            'RP-0003',
            'RP-0004',
        ], $results->pluck('customer_number')->all());

        $this->assertSame(
            ['guest'],
            $results->pluck('source')->unique()->values()->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'customer_number',
            'first_name',
            'last_name',
            'source',
        ]);
    }
}