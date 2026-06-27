<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry118InventoryMovementSummaryByType;

class Qry118InventoryMovementSummaryByTypeTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_inventory_movement_summary_by_type(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry118InventoryMovementSummaryByType::class,
        );

        $expected = [
            'adjustment' => [6, -23],
            'opening' => [8, 144],
            'purchase' => [1, 10],
            'return' => [1, 1],
            'sale' => [7, -14],
            'transfer_out' => [1, -10],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('type')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->type],
                [
                    (int) $row->movement_count,
                    (int) $row->net_quantity,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'type',
            'movement_count',
            'net_quantity',
        ]);
    }
}
