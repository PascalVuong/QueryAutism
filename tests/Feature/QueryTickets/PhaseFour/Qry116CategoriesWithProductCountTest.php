<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry116CategoriesWithProductCount;

class Qry116CategoriesWithProductCountTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_categories_with_product_count(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry116CategoriesWithProductCount::class,
        );

        $expected = [
            'Clearance' => ['archived', 0],
            'Golf Shop' => ['active', 4],
            'Padel Shop' => ['active', 2],
            'Training Supplies' => ['active', 1],
            'Wellness Retail' => ['active', 2],
        ];

        $this->assertSame(
            array_keys($expected),
            $results->pluck('name')->all(),
        );

        foreach ($results as $category) {
            $this->assertSame(
                $expected[$category->name],
                [
                    $category->status,
                    (int) $category->products_count,
                ],
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'name',
            'status',
            'products_count',
        ]);
    }
}
