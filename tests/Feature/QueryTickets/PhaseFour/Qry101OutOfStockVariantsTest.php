<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry101OutOfStockVariants;

class Qry101OutOfStockVariantsTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_out_of_stock_variants(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry101OutOfStockVariants::class,
        );

        $this->assertSame([
            'GV-GLOVE-L',
        ], $results->pluck('sku')->all());

        $variant = $results->first();

        $this->assertTrue($variant->relationLoaded('inventoryLevels'));
        $this->assertCount(1, $variant->inventoryLevels);

        $level = $variant->inventoryLevels->first();

        $this->assertSame(0, $level->quantity_on_hand);
        $this->assertSame(0, $level->quantity_reserved);
        $this->assertTrue($level->relationLoaded('stockLocation'));
        $this->assertSame('GV-PRO', $level->stockLocation->code);

        $this->assertExactColumns($results, [
            'id',
            'product_id',
            'sku',
            'name',
        ]);
    }
}
