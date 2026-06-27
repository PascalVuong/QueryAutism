<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry114StockLocationInventoryReport;

class Qry114StockLocationInventoryReportTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_stock_location_inventory_report(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry114StockLocationInventoryReport::class,
        );

        $expected = [
            'GV-BACK' => [1, 40, 0, 40, 0],
            'GV-PRO' => [3, 23, 5, 18, 2],
            'RP-DESK' => [2, 13, 4, 9, 1],
            'SW-RECEPTION' => [1, 6, 1, 5, 0],
            'VO-STORAGE' => [1, 25, 0, 25, 0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('location_code')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->location_code],
                [
                    (int) $row->variant_count,
                    (int) $row->quantity_on_hand,
                    (int) $row->quantity_reserved,
                    (int) $row->available_quantity,
                    (int) $row->low_stock_count,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'location_code',
            'variant_count',
            'quantity_on_hand',
            'quantity_reserved',
            'available_quantity',
            'low_stock_count',
        ]);
    }
}
