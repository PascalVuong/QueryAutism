<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\QueryTickets\PhaseTwo\Qry051ActiveVenues;

class Qry051ActiveVenuesTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_active_venues(): void
    {
        $results = $this->runPhaseTwoTicket(
            Qry051ActiveVenues::class,
        );

        $this->assertSame([
            'green-valley-main-venue',
            'rotterdam-padel-hall',
            'serenity-spa',
            'venueops-training-centre',
        ], $results->pluck('slug')->all());

        $this->assertSame(
            ['active'],
            $results->pluck('status')->unique()->values()->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'name',
            'slug',
            'status',
            'city',
        ]);
    }
}