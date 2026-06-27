<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry011UsersWithProfiles;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry011UsersWithProfilesTest extends QueryTicketTestCase
{
    public function test_it_returns_users_with_eager_loaded_profiles(): void
    {
        $results = $this->runTicket(Qry011UsersWithProfiles::class);

        $expectedIds = DB::table('users')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->orderBy('users.email')
            ->pluck('users.id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($user) => $user->relationLoaded('profile')
                    && !is_null($user->profile),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'status',
        ]);
    }
}
