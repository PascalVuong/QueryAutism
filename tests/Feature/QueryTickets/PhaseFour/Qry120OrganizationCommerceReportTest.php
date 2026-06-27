<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry120OrganizationCommerceReport;

class Qry120OrganizationCommerceReportTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_organization_commerce_report(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry120OrganizationCommerceReport::class,
        );

        $expected = [
            'Dormant Event Hall' => [0, 0, 0, 0, 0, 0, 0.0, 0],
            'Green Valley Golf Club' => [2, 4, 4, 2, 4, 2, 110.0, 58],
            'Rotterdam Padel Centre' => [1, 2, 2, 1, 2, 1, 40.0, 9],
            'Serenity Wellness' => [1, 2, 2, 1, 1, 1, 90.0, 5],
            'VenueOps Leisure Group' => [1, 1, 1, 1, 1, 0, 0.0, 25],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('organization_name')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->organization_name],
                [
                    (int) $row->category_count,
                    (int) $row->product_count,
                    (int) $row->variant_count,
                    (int) $row->stock_location_count,
                    (int) $row->order_count,
                    (int) $row->completed_order_count,
                    (float) $row->sales_revenue,
                    (int) $row->available_stock,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'organization_name',
            'category_count',
            'product_count',
            'variant_count',
            'stock_location_count',
            'order_count',
            'completed_order_count',
            'sales_revenue',
            'available_stock',
        ]);
    }
}
