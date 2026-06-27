<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry106InventoryMovementTimeline;

class Qry106InventoryMovementTimelineTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_inventory_movement_timeline(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry106InventoryMovementTimeline::class,
        );

        $this->assertSame([
            'opening',
            'sale',
            'sale',
            'adjustment',
        ], $results->pluck('type')->all());

        $this->assertSame([
            30,
            -2,
            -1,
            -7,
        ], $results->pluck('quantity')->map(
            fn ($quantity) => (int) $quantity,
        )->all());

        $this->assertSame([
            30,
            28,
            27,
            20,
        ], $results->pluck('quantity_after')->map(
            fn ($quantity) => (int) $quantity,
        )->all());

        $this->assertExactColumns($results, [
            'id',
            'type',
            'quantity',
            'quantity_after',
            'occurred_at',
        ]);
    }
}
