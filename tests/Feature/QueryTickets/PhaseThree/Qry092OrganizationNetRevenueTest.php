<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry092OrganizationNetRevenue;

class Qry092OrganizationNetRevenueTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_organization_net_revenue(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry092OrganizationNetRevenue::class,
        );

        $expected = [
            'Dormant Event Hall' => [0, 0, 0.0, 0.0, 0.0],
            'Green Valley Golf Club' => [3, 1, 240.0, 0.0, 240.0],
            'Rotterdam Padel Centre' => [1, 1, 80.0, 20.0, 60.0],
            'Serenity Wellness' => [1, 0, 90.0, 90.0, 0.0],
            'VenueOps Leisure Group' => [0, 0, 0.0, 0.0, 0.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('organization_name')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->organization_name],
                [
                    (int) $row->successful_payment_count,
                    (int) $row->failed_payment_count,
                    (float) $row->gross_amount,
                    (float) $row->refund_amount,
                    (float) $row->net_revenue,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'organization_name',
            'successful_payment_count',
            'failed_payment_count',
            'gross_amount',
            'refund_amount',
            'net_revenue',
        ]);
    }
}
