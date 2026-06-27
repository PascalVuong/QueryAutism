<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry007ActiveOrPendingUsers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry007ActiveOrPendingUsersTest extends QueryTicketTestCase
{
    public function test_it_returns_active_or_pending_users(): void
    {
        $results = $this->runTicket(
            Qry007ActiveOrPendingUsers::class,
        );

        $this->assertSame([
            'multi.manager@queryautism.test',
            'never.logged.in@queryautism.test',
            'no.profile@queryautism.test',
            'owner@queryautism.test',
            'pending@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertSame(
            ['active', 'pending'],
            $results->pluck('status')->unique()->values()->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'status',
        ]);
    }
}