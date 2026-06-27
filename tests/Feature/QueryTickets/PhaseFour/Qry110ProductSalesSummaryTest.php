<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry110ProductSalesSummary;

class Qry110ProductSalesSummaryTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_product_sales_summary(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry110ProductSalesSummary::class,
        );

        $expected = [
            'GIFT-CARD' => [1, 50.0],
            'GOLF-BALLS' => [3, 85.0],
            'GOLF-GLOVE' => [1, 25.0],
            'GOLF-TOWEL' => [0, 0.0],
            'LEGACY-HOLDER' => [0, 0.0],
            'MASSAGE-OIL' => [2, 36.0],
            'PADEL-BALLS' => [2, 24.0],
            'PADEL-GRIP' => [2, 16.0],
            'TRAINING-NOTEBOOK' => [0, 0.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('product_code')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->product_code],
                [
                    (int) $row->sold_quantity,
                    (float) $row->line_revenue,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'product_code',
            'product_name',
            'sold_quantity',
            'line_revenue',
        ]);
    }
}
