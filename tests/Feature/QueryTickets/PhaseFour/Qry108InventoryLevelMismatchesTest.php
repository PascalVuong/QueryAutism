<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry108InventoryLevelMismatches;

class Qry108InventoryLevelMismatchesTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_inventory_level_mismatches(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry108InventoryLevelMismatches::class,
        );

        $this->assertCount(1, $results);

        $row = $results->first();

        $this->assertSame('RP-GRIP-BLK', $row->sku);
        $this->assertSame('RP-DESK', $row->location_code);
        $this->assertSame(1, (int) $row->stored_quantity);
        $this->assertSame(2, (int) $row->calculated_quantity);
        $this->assertSame(-1, (int) $row->difference);

        $this->assertResultColumns($results, [
            'sku',
            'location_code',
            'stored_quantity',
            'calculated_quantity',
            'difference',
        ]);
    }
}
