<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry077ReservationChargeTotals;

class Qry077ReservationChargeTotalsTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_charge_totals_per_reservation(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry077ReservationChargeTotals::class,
        );

        $expected = [
            'GV-RES-0001' => [120.0, 0.0, 120.0],
            'GV-RES-0002' => [70.0, 10.0, 60.0],
            'GV-RES-0003' => [150.0, 0.0, 150.0],
            'GV-RES-0004' => [100.0, 0.0, 100.0],
            'RP-RES-0001' => [80.0, 0.0, 80.0],
            'RP-RES-0002' => [80.0, 0.0, 80.0],
            'RP-RES-0003' => [0.0, 0.0, 0.0],
            'SW-RES-0001' => [90.0, 0.0, 90.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('reference_number')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->reference_number],
                [
                    (float) $row->debit_total,
                    (float) $row->credit_total,
                    (float) $row->net_total,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'reference_number',
            'debit_total',
            'credit_total',
            'net_total',
        ]);
    }
}
