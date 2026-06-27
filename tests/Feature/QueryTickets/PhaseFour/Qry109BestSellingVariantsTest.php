<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry109BestSellingVariants;

class Qry109BestSellingVariantsTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_best_selling_variants(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry109BestSellingVariants::class,
        );

        $expected = [
            'GV-BALL-12' => [3, 85.0],
            'RP-BALL-3' => [2, 24.0],
            'RP-GRIP-BLK' => [2, 16.0],
            'SW-OIL-250' => [2, 36.0],
            'GV-GLOVE-M' => [1, 25.0],
            'SW-GIFT-50' => [1, 50.0],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('sku')->all(),
        );

        foreach ($results as $row) {
            $this->assertSame(
                $expected[$row->sku],
                [
                    (int) $row->sold_quantity,
                    (float) $row->line_revenue,
                ],
            );
        }

        $this->assertResultColumns($results, [
            'sku',
            'variant_name',
            'sold_quantity',
            'line_revenue',
        ]);
    }
}
