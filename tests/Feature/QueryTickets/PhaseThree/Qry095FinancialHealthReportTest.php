<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry095FinancialHealthReport;

class Qry095FinancialHealthReportTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_financial_health_report(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry095FinancialHealthReport::class,
        );

        $expected = [
            'Dormant Event Hall' => [0, 0, 0, 0, 0.0, 0.0],
            'Green Valley Golf Club' => [4, 3, 1, 1, 240.0, 45.0],
            'Rotterdam Padel Centre' => [3, 1, 1, 1, 60.0, 20.0],
            'Serenity Wellness' => [1, 1, 0, 0, 0.0, 0.0],
            'VenueOps Leisure Group' => [0, 0, 0, 0, 0.0, 0.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('organization_name')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->organization_name],
                [
                    (int) $row->reservation_count,
                    (int) $row->successful_payment_count,
                    (int) $row->failed_payment_count,
                    (int) $row->underpaid_reservation_count,
                    (float) $row->net_revenue,
                    (float) $row->credit_balance_total,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'organization_name',
            'reservation_count',
            'successful_payment_count',
            'failed_payment_count',
            'underpaid_reservation_count',
            'net_revenue',
            'credit_balance_total',
        ]);
    }
}
