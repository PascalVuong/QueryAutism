<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry115InventoryAndSalesHealthReport;

class Qry115InventoryAndSalesHealthReportTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_inventory_and_sales_health_report(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry115InventoryAndSalesHealthReport::class,
        );

        $expected = [
            'Dormant Event Hall' => [0, 0, 0, 0, 0, 0.0, 0],
            'Green Valley Golf Club' => [4, 2, 58, 2, 2, 110.0, 0],
            'Rotterdam Padel Centre' => [2, 1, 9, 1, 1, 40.0, 1],
            'Serenity Wellness' => [2, 1, 5, 0, 1, 90.0, 0],
            'VenueOps Leisure Group' => [1, 1, 25, 0, 0, 0.0, 0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('organization_name')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->organization_name],
                [
                    (int) $row->product_count,
                    (int) $row->stock_location_count,
                    (int) $row->available_stock,
                    (int) $row->low_stock_count,
                    (int) $row->completed_order_count,
                    (float) $row->sales_revenue,
                    (int) $row->inventory_mismatch_count,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'organization_name',
            'product_count',
            'stock_location_count',
            'available_stock',
            'low_stock_count',
            'completed_order_count',
            'sales_revenue',
            'inventory_mismatch_count',
        ]);
    }
}
