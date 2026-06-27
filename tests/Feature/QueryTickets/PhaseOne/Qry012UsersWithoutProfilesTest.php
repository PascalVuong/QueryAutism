<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry012UsersWithoutProfiles;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry012UsersWithoutProfilesTest extends QueryTicketTestCase
{
    public function test_it_returns_users_without_profiles(): void
    {
        $results = $this->runTicket(Qry012UsersWithoutProfiles::class);

        $expectedIds = DB::table('users')
            ->leftJoin(
                'user_profiles',
                'user_profiles.user_id',
                '=',
                'users.id',
            )
            ->whereNull('user_profiles.id')
            ->orderBy('users.email')
            ->pluck('users.id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'status',
        ]);
    }
}
