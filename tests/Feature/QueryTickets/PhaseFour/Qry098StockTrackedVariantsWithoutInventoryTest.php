<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry098StockTrackedVariantsWithoutInventory;

class Qry098StockTrackedVariantsWithoutInventoryTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_stock_tracked_variants_without_inventory(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry098StockTrackedVariantsWithoutInventory::class,
        );

        $this->assertSame([
            'GV-TOWEL-BLUE',
        ], $results->pluck('sku')->all());

        $variant = $results->first();

        $this->assertTrue($variant->relationLoaded('product'));
        $this->assertSame('GOLF-TOWEL', $variant->product->code);
        $this->assertTrue($variant->product->is_stock_tracked);

        $this->assertExactColumns($results, [
            'id',
            'product_id',
            'sku',
            'name',
            'status',
        ]);
    }
}
