<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry083NetPaidAmountPerReservation;

class Qry083NetPaidAmountPerReservationTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_net_paid_amount_per_reservation(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry083NetPaidAmountPerReservation::class,
        );

        $expected = [
            'GV-RES-0001' => [120.0, 120.0],
            'GV-RES-0002' => [60.0, 60.0],
            'GV-RES-0003' => [150.0, 0.0],
            'GV-RES-0004' => [100.0, 60.0],
            'RP-RES-0001' => [80.0, 60.0],
            'RP-RES-0002' => [80.0, 0.0],
            'RP-RES-0003' => [0.0, 0.0],
            'SW-RES-0001' => [90.0, 0.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('reference_number')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->reference_number],
                [
                    (float) $row->reservation_total,
                    (float) $row->net_paid_amount,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'reference_number',
            'reservation_total',
            'net_paid_amount',
        ]);
    }
}
