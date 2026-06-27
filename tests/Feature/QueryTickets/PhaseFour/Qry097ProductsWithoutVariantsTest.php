<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use App\QueryTickets\PhaseFour\Qry097ProductsWithoutVariants;

class Qry097ProductsWithoutVariantsTest extends PhaseFourQueryTicketTestCase
{
    public function test_it_returns_products_without_variants(): void
    {
        $results = $this->runPhaseFourTicket(
            Qry097ProductsWithoutVariants::class,
        );

        $this->assertSame([
            'LEGACY-HOLDER',
        ], $results->pluck('code')->all());

        $this->assertSame('archived', $results->first()->status);

        $this->assertExactColumns($results, [
            'id',
            'code',
            'name',
            'status',
            'product_type',
        ]);
    }
}
