<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry107InventoryRunningBalances;

class Qry107InventoryRunningBalancesTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_inventory_running_balances(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry107InventoryRunningBalances::class,
        );

        $this->assertCount(24, $results);

        $this->assertSame([
            'GV-BALL-12|GV-BACK|50',
            'GV-BALL-12|GV-BACK|40',
            'GV-BALL-12|GV-PRO|30',
            'GV-BALL-12|GV-PRO|28',
            'GV-BALL-12|GV-PRO|27',
            'GV-BALL-12|GV-PRO|20',
            'GV-GLOVE-L|GV-PRO|4',
            'GV-GLOVE-L|GV-PRO|0',
            'GV-GLOVE-M|GV-PRO|5',
            'GV-GLOVE-M|GV-PRO|4',
            'GV-GLOVE-M|GV-PRO|3',
            'RP-BALL-3|RP-DESK|20',
            'RP-BALL-3|RP-DESK|18',
            'RP-BALL-3|RP-DESK|19',
            'RP-BALL-3|RP-DESK|12',
            'RP-GRIP-BLK|RP-DESK|5',
            'RP-GRIP-BLK|RP-DESK|3',
            'RP-GRIP-BLK|RP-DESK|2',
            'SW-OIL-250|SW-RECEPTION|10',
            'SW-OIL-250|SW-RECEPTION|8',
            'SW-OIL-250|SW-RECEPTION|6',
            'VO-NOTE-A5|VO-STORAGE|20',
            'VO-NOTE-A5|VO-STORAGE|30',
            'VO-NOTE-A5|VO-STORAGE|25',
        ], $results->map(
            fn ($row) => $row->sku
                .'|'.$row->location_code
                .'|'.(int) $row->running_quantity,
        )->all());

        $this->assertSame(
            $results->map(
                fn ($row) => (int) $row->quantity_after,
            )->all(),
            $results->map(
                fn ($row) => (int) $row->running_quantity,
            )->all(),
        );

        $this->assertResultColumns($results, [
            'sku',
            'location_code',
            'type',
            'quantity',
            'quantity_after',
            'running_quantity',
            'occurred_at',
        ]);
    }
}
