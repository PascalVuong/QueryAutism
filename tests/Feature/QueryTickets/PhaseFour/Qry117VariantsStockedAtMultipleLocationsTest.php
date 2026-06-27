<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry117VariantsStockedAtMultipleLocations;

class Qry117VariantsStockedAtMultipleLocationsTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_variants_stocked_at_multiple_locations(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry117VariantsStockedAtMultipleLocations::class,
        );

        $this->assertCount(1, $results);

        $variant = $results->first();

        $this->assertSame('GV-BALL-12', $variant->sku);
        $this->assertSame(2, (int) $variant->inventory_levels_count);
        $this->assertTrue($variant->relationLoaded('inventoryLevels'));

        foreach ($variant->inventoryLevels as $level) {
            $this->assertTrue($level->relationLoaded('stockLocation'));
        }

        $this->assertSame([
            'GV-BACK',
            'GV-PRO',
        ], $variant->inventoryLevels
            ->pluck('stockLocation.code')
            ->sort()
            ->values()
            ->all());

        $this->assertExactColumns($results, [
            'id',
            'product_id',
            'sku',
            'name',
            'status',
            'inventory_levels_count',
        ]);
    }
}
