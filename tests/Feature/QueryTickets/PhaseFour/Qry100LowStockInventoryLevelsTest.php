<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry100LowStockInventoryLevels;

class Qry100LowStockInventoryLevelsTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_low_stock_inventory_levels(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry100LowStockInventoryLevels::class,
        );

        $this->assertSame([
            'GV-GLOVE-L',
            'GV-GLOVE-M',
            'RP-GRIP-BLK',
        ], $results->map(
            fn ($level) => $level->productVariant->sku,
        )->all());

        foreach ($results as $level) {
            $this->assertTrue(
                $level->relationLoaded('productVariant'),
            );
            $this->assertTrue(
                $level->productVariant->relationLoaded('product'),
            );
            $this->assertTrue(
                $level->relationLoaded('stockLocation'),
            );

            $available = $level->quantity_on_hand
                - $level->quantity_reserved;

            $this->assertLessThanOrEqual(
                $level->reorder_point,
                $available,
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'product_variant_id',
            'stock_location_id',
            'quantity_on_hand',
            'quantity_reserved',
            'reorder_point',
        ]);
    }
}
