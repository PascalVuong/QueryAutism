<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry096ActiveProductsWithCategory;

class Qry096ActiveProductsWithCategoryTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_active_products_with_category(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry096ActiveProductsWithCategory::class,
        );

        $this->assertSame([
            'GIFT-CARD',
            'GOLF-BALLS',
            'GOLF-GLOVE',
            'GOLF-TOWEL',
            'MASSAGE-OIL',
            'PADEL-BALLS',
            'PADEL-GRIP',
            'TRAINING-NOTEBOOK',
        ], $results->pluck('code')->all());

        $categories = [
            'GIFT-CARD' => 'Wellness Retail',
            'GOLF-BALLS' => 'Golf Shop',
            'GOLF-GLOVE' => 'Golf Shop',
            'GOLF-TOWEL' => 'Golf Shop',
            'MASSAGE-OIL' => 'Wellness Retail',
            'PADEL-BALLS' => 'Padel Shop',
            'PADEL-GRIP' => 'Padel Shop',
            'TRAINING-NOTEBOOK' => 'Training Supplies',
        ];

        foreach ($results as $product) {
            $this->assertSame('active', $product->status);
            $this->assertTrue($product->relationLoaded('category'));
            $this->assertSame(
                $categories[$product->code],
                $product->category->name,
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'product_category_id',
            'code',
            'name',
            'status',
            'product_type',
        ]);
    }
}
