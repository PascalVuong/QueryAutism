<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry005RecentLogins;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry005RecentLoginsTest extends QueryTicketTestCase
{
    public function test_it_returns_logins_from_the_last_seven_days(): void
    {
        $results = $this->runTicket(Qry005RecentLogins::class);

        $this->assertSame([
            'owner@queryautism.test',
            'multi.manager@queryautism.test',
            'no.profile@queryautism.test',
            'pending@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertTrue(
            $results->every(
                fn ($user) => $user->last_login_at->gte(
                    now()->subDays(7),
                ),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'status',
            'last_login_at',
        ]);
    }
}