<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry018UsersWithOrganizations;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry018UsersWithOrganizationsTest extends QueryTicketTestCase
{
    public function test_it_eager_loads_organizations_for_users(): void
    {
        $results = $this->runTicket(Qry018UsersWithOrganizations::class);

        $expectedIds = DB::table('users')
            ->join(
                'organization_users',
                'organization_users.user_id',
                '=',
                'users.id',
            )
            ->groupBy('users.id', 'users.email')
            ->orderBy('users.email')
            ->pluck('users.id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($user) => $user->relationLoaded('organizations')
                    && $user->organizations->isNotEmpty(),
            ),
        );

        foreach ($results as $user) {
            $this->assertSame(
                $user->organizations->pluck('name')->sort()->values()->all(),
                $user->organizations->pluck('name')->all(),
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'status',
        ]);
    }
}
