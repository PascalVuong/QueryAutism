<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry003UsersWhoNeverLoggedIn;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry003UsersWhoNeverLoggedInTest extends QueryTicketTestCase
{
    public function test_it_returns_users_without_a_last_login(): void
    {
        $results = $this->runTicket(
            Qry003UsersWhoNeverLoggedIn::class,
        );

        $this->assertSame([
            'never.logged.in@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertTrue(
            $results->every(
                fn ($user) => is_null($user->last_login_at),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'last_login_at',
        ]);
    }
}