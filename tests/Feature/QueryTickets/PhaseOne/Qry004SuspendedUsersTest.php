<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry004SuspendedUsers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry004SuspendedUsersTest extends QueryTicketTestCase
{
    public function test_it_returns_suspended_users(): void
    {
        $results = $this->runTicket(Qry004SuspendedUsers::class);

        $this->assertSame([
            'suspended@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertSame(
            ['suspended'],
            $results->pluck('status')->unique()->values()->all(),
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