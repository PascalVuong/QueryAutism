<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry076CurrentlyActivePriceRules;

class Qry076CurrentlyActivePriceRulesTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_currently_active_price_rules(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry076CurrentlyActivePriceRules::class,
        );

        $this->assertSame([
            'GREEN-BASE',
            'PADEL-BASE',
            'WELLNESS-BASE',
            'GREEN-DISCOUNT',
            'GREEN-SURCHARGE',
        ], $results->pluck('code')->all());

        $this->assertTrue(
            $results->every(fn ($rule) => $rule->status === 'active'),
        );

        $this->assertExactColumns($results, [
            'id',
            'code',
            'name',
            'type',
            'amount',
            'percentage',
            'priority',
        ]);
    }
}
