<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry002UnverifiedUsers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry002UnverifiedUsersTest extends QueryTicketTestCase
{
    public function test_it_returns_unverified_users(): void
    {
        $results = $this->runTicket(Qry002UnverifiedUsers::class);

        $this->assertSame([
            'suspended@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertTrue(
            $results->every(
                fn ($user) => is_null($user->email_verified_at),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'email_verified_at',
            'status',
        ]);
    }
}