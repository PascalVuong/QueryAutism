<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry019UsersInMultipleOrganizations;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry019UsersInMultipleOrganizationsTest extends QueryTicketTestCase
{
    public function test_it_returns_users_in_multiple_organizations(): void
    {
        $results = $this->runTicket(
            Qry019UsersInMultipleOrganizations::class,
        );

        $expectedIds = DB::table('users')
            ->join(
                'organization_users',
                'organization_users.user_id',
                '=',
                'users.id',
            )
            ->groupBy('users.id', 'users.email')
            ->havingRaw('COUNT(organization_users.organization_id) > 1')
            ->orderBy('users.email')
            ->pluck('users.id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($user) => $user->relationLoaded('organizations')
                    && $user->organizations->count() > 1,
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
