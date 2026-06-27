<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry111ProductRevenueByOrganization;

class Qry111ProductRevenueByOrganizationTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_product_revenue_by_organization(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry111ProductRevenueByOrganization::class,
        );

        $expected = [
            'Dormant Event Hall' => [0, 0.0],
            'Green Valley Golf Club' => [4, 110.0],
            'Rotterdam Padel Centre' => [4, 40.0],
            'Serenity Wellness' => [3, 86.0],
            'VenueOps Leisure Group' => [0, 0.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('organization_name')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->organization_name],
                [
                    (int) $row->sold_quantity,
                    (float) $row->line_revenue,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'organization_name',
            'sold_quantity',
            'line_revenue',
        ]);
    }
}
