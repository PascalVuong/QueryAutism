<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry016ChildOrganizationsWithParent;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry016ChildOrganizationsWithParentTest extends QueryTicketTestCase
{
    public function test_it_eager_loads_the_parent_for_child_organizations(): void
    {
        $results = $this->runTicket(
            Qry016ChildOrganizationsWithParent::class,
        );

        $expectedIds = DB::table('organizations')
            ->whereNotNull('parent_id')
            ->orderBy('name')
            ->pluck('id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($organization) => $organization->relationLoaded('parent')
                    && !is_null($organization->parent),
            ),
        );

        foreach ($results as $organization) {
            $this->assertSame(
                ['id', 'name', 'slug', 'status'],
                array_keys($organization->parent->getAttributes()),
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'parent_id',
            'name',
            'slug',
            'status',
        ]);
    }
}
