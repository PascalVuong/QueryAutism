<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry093VenueNetRevenue;

class Qry093VenueNetRevenueTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_venue_net_revenue(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry093VenueNetRevenue::class,
        );

        $expected = [
            'Green Valley Main Venue' => [3, 240.0, 0.0, 240.0],
            'Rotterdam Padel Hall' => [1, 80.0, 20.0, 60.0],
            'Serenity Spa' => [1, 90.0, 90.0, 0.0],
            'VenueOps Training Centre' => [0, 0.0, 0.0, 0.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('venue_name')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->venue_name],
                [
                    (int) $row->successful_payment_count,
                    (float) $row->gross_amount,
                    (float) $row->refund_amount,
                    (float) $row->net_revenue,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'venue_name',
            'successful_payment_count',
            'gross_amount',
            'refund_amount',
            'net_revenue',
        ]);
    }
}
