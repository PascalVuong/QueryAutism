<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry049CustomerMembershipReport;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry049CustomerMembershipReportTest extends QueryTicketTestCase
{
    public function test_it_returns_the_customer_membership_report(): void
    {
        $results = $this->runTicket(
            Qry049CustomerMembershipReport::class,
        );

        $this->assertSame([
            'GV-MEM-0001',
            'GV-MEM-0002',
            'GV-MEM-0003',
            'GV-MEM-0004',
            'GV-MEM-0005',
            'GV-MEM-0006',
            'RP-MEM-0001',
            'RP-MEM-0002',
        ], $results->pluck('membership_number')->all());

        $this->assertSame([
            'Green Valley Golf Club',
            'Green Valley Golf Club',
            'Green Valley Golf Club',
            'Green Valley Golf Club',
            'Green Valley Golf Club',
            'Green Valley Golf Club',
            'Rotterdam Padel Centre',
            'Rotterdam Padel Centre',
        ], $results->pluck('organization_name')->all());

        $this->assertSame([
            425.0,
            0.0,
            45.0,
            40.0,
            45.0,
            400.0,
            35.0,
            850.0,
        ], $results->map(
            fn ($row) => round((float) $row->agreed_price, 2),
        )->all());

        $this->assertResultColumns($results, [
            'organization_name',
            'customer_number',
            'first_name',
            'last_name',
            'membership_number',
            'plan_name',
            'membership_status',
            'agreed_price',
            'ends_at',
        ]);
    }
}