<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use App\Models\Venue;
use App\QueryTickets\PhaseTwo\Qry052FacilitiesForGreenValleyVenue;

class Qry052FacilitiesForGreenValleyVenueTest extends PhaseTwoQueryTicketTestCase
{
    public function test_it_returns_facilities_for_green_valley(): void
    {
        $results = $this->runPhaseTwoTicket(
            Qry052FacilitiesForGreenValleyVenue::class,
        );

        $this->assertSame([
            'CLUB',
            'COURSE',
        ], $results->pluck('code')->all());

        $venueId = Venue::query()
            ->where('slug', 'green-valley-main-venue')
            ->value('id');

        $this->assertTrue(
            $results->every(
                fn ($facility) => $facility->venue_id === $venueId,
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'venue_id',
            'code',
            'name',
            'type',
            'status',
        ]);
    }
}