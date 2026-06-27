<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry075OrganizationReservationHealthReport;

class Qry075OrganizationReservationHealthReportTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_the_organization_health_report(): void
    {
        $results = $this->runTicket(
            Qry075OrganizationReservationHealthReport::class,
        );

        $this->assertSame([
            'Dormant Event Hall',
            'Green Valley Golf Club',
            'Rotterdam Padel Centre',
            'Serenity Wellness',
            'VenueOps Leisure Group',
        ], $results->pluck('organization_name')->all());

        $expected = [
            'Dormant Event Hall' => [0, 0, 0, 0.0],
            'Green Valley Golf Club' => [4, 3, 1, 280.0],
            'Rotterdam Padel Centre' => [3, 2, 0, 160.0],
            'Serenity Wellness' => [1, 0, 0, 90.0],
            'VenueOps Leisure Group' => [0, 0, 0, 0.0],
        ];

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->organization_name],
                [
                    (int) $row->reservation_count,
                    (int) $row->upcoming_count,
                    (int) $row->cancelled_count,
                    (float) $row->revenue_total,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'organization_id',
            'organization_name',
            'reservation_count',
            'upcoming_count',
            'cancelled_count',
            'revenue_total',
        ]);
    }
}
