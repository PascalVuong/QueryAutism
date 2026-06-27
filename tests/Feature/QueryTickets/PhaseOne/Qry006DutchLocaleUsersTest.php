<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry006DutchLocaleUsers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry006DutchLocaleUsersTest extends QueryTicketTestCase
{
    public function test_it_returns_users_with_the_dutch_locale(): void
    {
        $results = $this->runTicket(Qry006DutchLocaleUsers::class);

        $this->assertSame([
            'never.logged.in@queryautism.test',
            'no.profile@queryautism.test',
            'owner@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertSame(
            ['nl'],
            $results->pluck('locale')->unique()->values()->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'locale',
        ]);
    }
}