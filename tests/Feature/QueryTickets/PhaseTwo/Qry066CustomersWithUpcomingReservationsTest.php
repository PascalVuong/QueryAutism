<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry066CustomersWithUpcomingReservations;

class Qry066CustomersWithUpcomingReservationsTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_customers_with_upcoming_reservations(): void
    {
        $results = $this->runTicket(
            Qry066CustomersWithUpcomingReservations::class,
        );

        $this->assertSame([
            'GV-0001',
            'GV-0002',
            'GV-0004',
            'RP-0001',
            'RP-0002',
        ], $results->pluck('customer_number')->all());

        $this->assertExactColumns($results, [
            'id',
            'customer_number',
            'first_name',
            'last_name',
        ]);
    }
}