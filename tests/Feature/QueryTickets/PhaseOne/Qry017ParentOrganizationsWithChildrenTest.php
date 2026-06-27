<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry017ParentOrganizationsWithChildren;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry017ParentOrganizationsWithChildrenTest extends QueryTicketTestCase
{
    public function test_it_eager_loads_ordered_child_organizations(): void
    {
        $results = $this->runTicket(
            Qry017ParentOrganizationsWithChildren::class,
        );

        $expectedIds = DB::table('organizations as parents')
            ->join(
                'organizations as children',
                'children.parent_id',
                '=',
                'parents.id',
            )
            ->groupBy('parents.id', 'parents.name')
            ->orderBy('parents.name')
            ->pluck('parents.id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($organization) => $organization->relationLoaded('children')
                    && $organization->children->isNotEmpty(),
            ),
        );

        foreach ($results as $organization) {
            $this->assertSame(
                $organization->children->pluck('name')->sort()->values()->all(),
                $organization->children->pluck('name')->all(),
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
