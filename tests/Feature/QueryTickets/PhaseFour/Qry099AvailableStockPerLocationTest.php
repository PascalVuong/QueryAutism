<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry099AvailableStockPerLocation;

class Qry099AvailableStockPerLocationTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_available_stock_per_location(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry099AvailableStockPerLocation::class,
        );

        $this->assertSame([
            'GV-BACK|GV-BALL-12',
            'GV-PRO|GV-BALL-12',
            'GV-PRO|GV-GLOVE-L',
            'GV-PRO|GV-GLOVE-M',
            'RP-DESK|RP-BALL-3',
            'RP-DESK|RP-GRIP-BLK',
            'SW-RECEPTION|SW-OIL-250',
            'VO-STORAGE|VO-NOTE-A5',
        ], $results->map(
            fn ($row) => $row->location_code.'|'.$row->sku,
        )->all());

        $this->assertSame([
            40,
            16,
            0,
            2,
            8,
            1,
            5,
            25,
        ], $results->map(
            fn ($row) => (int) $row->available_quantity,
        )->all());

        $this->assertResultColumns($results, [
            'location_code',
            'sku',
            'quantity_on_hand',
            'quantity_reserved',
            'available_quantity',
        ]);
    }
}
