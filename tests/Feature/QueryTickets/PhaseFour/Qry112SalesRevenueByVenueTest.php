<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry112SalesRevenueByVenue;

class Qry112SalesRevenueByVenueTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_sales_revenue_by_venue(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry112SalesRevenueByVenue::class,
        );

        $expected = [
            'Green Valley Main Venue' => [2, 110.0],
            'Rotterdam Padel Hall' => [1, 40.0],
            'Serenity Spa' => [1, 90.0],
            'VenueOps Training Centre' => [0, 0.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('venue_name')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->venue_name],
                [
                    (int) $row->completed_order_count,
                    (float) $row->sales_revenue,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'venue_name',
            'completed_order_count',
            'sales_revenue',
        ]);
    }
}
