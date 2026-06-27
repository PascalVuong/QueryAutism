<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Resource;
use App\QueryTickets\PhaseTwo\Qry060ReservationsWithMultipleResources;

class Qry060ReservationsWithMultipleResourcesTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_reservations_with_multiple_items(): void
    {
        $results = $this->runPhaseTwoTicket(
            Qry060ReservationsWithMultipleResources::class,
            function (): void {
                $reservation = Reservation::query()
                    ->where('reference_number', 'GV-RES-0001')
                    ->firstOrFail();

                $resource = Resource::query()
                    ->where('code', 'SIM-1')
                    ->firstOrFail();

                ReservationItem::query()->create([
                    'reservation_id' => $reservation->id,
                    'resource_id' => $resource->id,
                    'starts_at' => $reservation->starts_at,
                    'ends_at' => $reservation->ends_at,
                    'quantity' => 1,
                    'unit_price' => 0,
                    'total_price' => 0,
                    'status' => 'reserved',
                ]);
            },
        );

        $this->assertSame([
            'GV-RES-0001',
        ], $results->pluck('reference_number')->all());

        $this->assertSame(
            [2],
            $results->pluck('items_count')->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'reference_number',
            'items_count',
        ]);
    }
}