#!/usr/bin/env bash
set -euo pipefail

if [[ ! -f artisan ]]; then
    echo "Run this script from the QueryAutism project root." >&2
    exit 1
fi

registry="app/QueryTickets/QueryTicketRegistry.php"
test_helper="tests/Feature/QueryTickets/QueryTicketTestCase.php"

if [[ ! -f "$registry" ]]; then
    echo "Missing expected file: $registry" >&2
    exit 1
fi

if [[ ! -f "$test_helper" ]]; then
    echo "Missing expected file: $test_helper" >&2
    exit 1
fi

mkdir -p app/QueryTickets/PhaseOne
mkdir -p tests/Feature/QueryTickets/PhaseOne

targets=(
    app/QueryTickets/PhaseOne/Qry011UsersWithProfiles.php
    app/QueryTickets/PhaseOne/Qry012UsersWithoutProfiles.php
    app/QueryTickets/PhaseOne/Qry013CustomersWithoutAccounts.php
    app/QueryTickets/PhaseOne/Qry014CustomersWithUserAccounts.php
    app/QueryTickets/PhaseOne/Qry015CustomersWithProfiles.php
    app/QueryTickets/PhaseOne/Qry016ChildOrganizationsWithParent.php
    app/QueryTickets/PhaseOne/Qry017ParentOrganizationsWithChildren.php
    app/QueryTickets/PhaseOne/Qry018UsersWithOrganizations.php
    app/QueryTickets/PhaseOne/Qry019UsersInMultipleOrganizations.php
    app/QueryTickets/PhaseOne/Qry020MembershipsWithCustomerAndPlan.php
    tests/Feature/QueryTickets/PhaseOne/Qry011UsersWithProfilesTest.php
    tests/Feature/QueryTickets/PhaseOne/Qry012UsersWithoutProfilesTest.php
    tests/Feature/QueryTickets/PhaseOne/Qry013CustomersWithoutAccountsTest.php
    tests/Feature/QueryTickets/PhaseOne/Qry014CustomersWithUserAccountsTest.php
    tests/Feature/QueryTickets/PhaseOne/Qry015CustomersWithProfilesTest.php
    tests/Feature/QueryTickets/PhaseOne/Qry016ChildOrganizationsWithParentTest.php
    tests/Feature/QueryTickets/PhaseOne/Qry017ParentOrganizationsWithChildrenTest.php
    tests/Feature/QueryTickets/PhaseOne/Qry018UsersWithOrganizationsTest.php
    tests/Feature/QueryTickets/PhaseOne/Qry019UsersInMultipleOrganizationsTest.php
    tests/Feature/QueryTickets/PhaseOne/Qry020MembershipsWithCustomerAndPlanTest.php
)

for target in "${targets[@]}"; do
    if [[ -e "$target" ]]; then
        echo "Refusing to overwrite existing file: $target" >&2
        exit 1
    fi
done

cat > app/QueryTickets/PhaseOne/Qry011UsersWithProfiles.php <<'PHP'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry011UsersWithProfiles extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-011';
    }

    public function title(): string
    {
        return 'Users with profiles';
    }

    public function description(): string
    {
        return 'Return users who have a profile. Eager load the profile '
            .'and order the users by email address.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'with',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-011 has not been solved yet.');
    }
}
PHP

cat > app/QueryTickets/PhaseOne/Qry012UsersWithoutProfiles.php <<'PHP'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry012UsersWithoutProfiles extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-012';
    }

    public function title(): string
    {
        return 'Users without profiles';
    }

    public function description(): string
    {
        return 'Return users who do not have a profile. '
            .'Order them by email address.';
    }

    public function concepts(): array
    {
        return [
            'doesntHave',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-012 has not been solved yet.');
    }
}
PHP

cat > app/QueryTickets/PhaseOne/Qry013CustomersWithoutAccounts.php <<'PHP'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry013CustomersWithoutAccounts extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-013';
    }

    public function title(): string
    {
        return 'Customers without user accounts';
    }

    public function description(): string
    {
        return 'Return customers that are not linked to a user account. '
            .'Order them by customer number.';
    }

    public function concepts(): array
    {
        return [
            'whereNull',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'user_id',
            'customer_number',
            'first_name',
            'last_name',
            'email',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-013 has not been solved yet.');
    }
}
PHP

cat > app/QueryTickets/PhaseOne/Qry014CustomersWithUserAccounts.php <<'PHP'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry014CustomersWithUserAccounts extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-014';
    }

    public function title(): string
    {
        return 'Customers with user accounts';
    }

    public function description(): string
    {
        return 'Return customers linked to a user account. Eager load '
            .'each user with only id, name, email and status. Order the '
            .'customers by customer number.';
    }

    public function concepts(): array
    {
        return [
            'whereNotNull',
            'constrained eager loading',
            'with',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'user_id',
            'customer_number',
            'first_name',
            'last_name',
            'email',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-014 has not been solved yet.');
    }
}
PHP

cat > app/QueryTickets/PhaseOne/Qry015CustomersWithProfiles.php <<'PHP'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry015CustomersWithProfiles extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-015';
    }

    public function title(): string
    {
        return 'Customers with profiles';
    }

    public function description(): string
    {
        return 'Return customers who have a customer profile. Eager load '
            .'the profile and order the customers by customer number.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'with',
            'hasOne relation',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'customer_number',
            'first_name',
            'last_name',
            'email',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-015 has not been solved yet.');
    }
}
PHP

cat > app/QueryTickets/PhaseOne/Qry016ChildOrganizationsWithParent.php <<'PHP'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry016ChildOrganizationsWithParent extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-016';
    }

    public function title(): string
    {
        return 'Child organizations with parent';
    }

    public function description(): string
    {
        return 'Return organizations that have a parent. Eager load the '
            .'parent with only id, name, slug and status. Order the child '
            .'organizations by name.';
    }

    public function concepts(): array
    {
        return [
            'whereNotNull',
            'belongsTo relation',
            'constrained eager loading',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'parent_id',
            'name',
            'slug',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-016 has not been solved yet.');
    }
}
PHP

cat > app/QueryTickets/PhaseOne/Qry017ParentOrganizationsWithChildren.php <<'PHP'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry017ParentOrganizationsWithChildren extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-017';
    }

    public function title(): string
    {
        return 'Parent organizations with children';
    }

    public function description(): string
    {
        return 'Return organizations that have child organizations. '
            .'Eager load the children, order the parents by name and '
            .'order each collection of children by name.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'hasMany relation',
            'ordered eager loading',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'parent_id',
            'name',
            'slug',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-017 has not been solved yet.');
    }
}
PHP

cat > app/QueryTickets/PhaseOne/Qry018UsersWithOrganizations.php <<'PHP'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry018UsersWithOrganizations extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-018';
    }

    public function title(): string
    {
        return 'Users with organizations';
    }

    public function description(): string
    {
        return 'Return users who belong to at least one organization. '
            .'Eager load their organizations, order users by email and '
            .'order each collection of organizations by name.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'belongsToMany relation',
            'ordered eager loading',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-018 has not been solved yet.');
    }
}
PHP

cat > app/QueryTickets/PhaseOne/Qry019UsersInMultipleOrganizations.php <<'PHP'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry019UsersInMultipleOrganizations extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-019';
    }

    public function title(): string
    {
        return 'Users in multiple organizations';
    }

    public function description(): string
    {
        return 'Return users who belong to more than one organization. '
            .'Eager load their organizations and order users by email.';
    }

    public function concepts(): array
    {
        return [
            'has with count operator',
            'belongsToMany relation',
            'with',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-019 has not been solved yet.');
    }
}
PHP

cat > app/QueryTickets/PhaseOne/Qry020MembershipsWithCustomerAndPlan.php <<'PHP'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry020MembershipsWithCustomerAndPlan extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-020';
    }

    public function title(): string
    {
        return 'Memberships with customer and plan';
    }

    public function description(): string
    {
        return 'Return every membership with its customer and membership '
            .'plan eager loaded. Select only the requested relation '
            .'columns and order memberships by id.';
    }

    public function concepts(): array
    {
        return [
            'multiple eager loads',
            'belongsTo relations',
            'constrained eager loading',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'customer_id',
            'membership_plan_id',
            'status',
            'starts_at',
            'ends_at',
            'agreed_price',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-020 has not been solved yet.');
    }
}
PHP

cat > tests/Feature/QueryTickets/PhaseOne/Qry011UsersWithProfilesTest.php <<'PHP'
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
PHP

cat > tests/Feature/QueryTickets/PhaseOne/Qry012UsersWithoutProfilesTest.php <<'PHP'
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
PHP

cat > tests/Feature/QueryTickets/PhaseOne/Qry013CustomersWithoutAccountsTest.php <<'PHP'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry013CustomersWithoutAccounts;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry013CustomersWithoutAccountsTest extends QueryTicketTestCase
{
    public function test_it_returns_customers_without_user_accounts(): void
    {
        $results = $this->runTicket(Qry013CustomersWithoutAccounts::class);

        $expectedIds = DB::table('customers')
            ->whereNull('user_id')
            ->orderBy('customer_number')
            ->pluck('id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(fn ($customer) => is_null($customer->user_id)),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'user_id',
            'customer_number',
            'first_name',
            'last_name',
            'email',
            'status',
        ]);
    }
}
PHP

cat > tests/Feature/QueryTickets/PhaseOne/Qry014CustomersWithUserAccountsTest.php <<'PHP'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry014CustomersWithUserAccounts;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry014CustomersWithUserAccountsTest extends QueryTicketTestCase
{
    public function test_it_eager_loads_users_for_linked_customers(): void
    {
        $results = $this->runTicket(Qry014CustomersWithUserAccounts::class);

        $expectedIds = DB::table('customers')
            ->whereNotNull('user_id')
            ->orderBy('customer_number')
            ->pluck('id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($customer) => $customer->relationLoaded('user')
                    && !is_null($customer->user),
            ),
        );

        foreach ($results as $customer) {
            $this->assertSame(
                ['id', 'name', 'email', 'status'],
                array_keys($customer->user->getAttributes()),
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'user_id',
            'customer_number',
            'first_name',
            'last_name',
            'email',
            'status',
        ]);
    }
}
PHP

cat > tests/Feature/QueryTickets/PhaseOne/Qry015CustomersWithProfilesTest.php <<'PHP'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry015CustomersWithProfiles;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry015CustomersWithProfilesTest extends QueryTicketTestCase
{
    public function test_it_returns_customers_with_eager_loaded_profiles(): void
    {
        $results = $this->runTicket(Qry015CustomersWithProfiles::class);

        $expectedIds = DB::table('customers')
            ->join(
                'customer_profiles',
                'customer_profiles.customer_id',
                '=',
                'customers.id',
            )
            ->orderBy('customers.customer_number')
            ->pluck('customers.id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($customer) => $customer->relationLoaded('profile')
                    && !is_null($customer->profile),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'customer_number',
            'first_name',
            'last_name',
            'email',
            'status',
        ]);
    }
}
PHP

cat > tests/Feature/QueryTickets/PhaseOne/Qry016ChildOrganizationsWithParentTest.php <<'PHP'
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
PHP

cat > tests/Feature/QueryTickets/PhaseOne/Qry017ParentOrganizationsWithChildrenTest.php <<'PHP'
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
PHP

cat > tests/Feature/QueryTickets/PhaseOne/Qry018UsersWithOrganizationsTest.php <<'PHP'
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
PHP

cat > tests/Feature/QueryTickets/PhaseOne/Qry019UsersInMultipleOrganizationsTest.php <<'PHP'
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
PHP

cat > tests/Feature/QueryTickets/PhaseOne/Qry020MembershipsWithCustomerAndPlanTest.php <<'PHP'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry020MembershipsWithCustomerAndPlan;
use Illuminate\Support\Facades\DB;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry020MembershipsWithCustomerAndPlanTest extends QueryTicketTestCase
{
    public function test_it_eager_loads_customer_and_plan(): void
    {
        $results = $this->runTicket(
            Qry020MembershipsWithCustomerAndPlan::class,
        );

        $expectedIds = DB::table('memberships')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        $this->assertSame($expectedIds, $results->pluck('id')->all());
        $this->assertNotEmpty($results);
        $this->assertTrue(
            $results->every(
                fn ($membership) => $membership->relationLoaded('customer')
                    && $membership->relationLoaded('plan')
                    && !is_null($membership->customer)
                    && !is_null($membership->plan),
            ),
        );

        foreach ($results as $membership) {
            $this->assertSame(
                ['id', 'customer_number', 'first_name', 'last_name'],
                array_keys($membership->customer->getAttributes()),
            );
            $this->assertSame(
                ['id', 'code', 'name', 'price', 'status'],
                array_keys($membership->plan->getAttributes()),
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'customer_id',
            'membership_plan_id',
            'status',
            'starts_at',
            'ends_at',
            'agreed_price',
        ]);
    }
}
PHP

php <<'PHP'
<?php

$path = 'app/QueryTickets/QueryTicketRegistry.php';
$contents = file_get_contents($path);

$importMarker = "use App\\QueryTickets\\PhaseOne\\Qry010ActiveMembershipPlansByPrice;\n";
$listMarker = "        Qry010ActiveMembershipPlansByPrice::class,\n";

$imports = <<<'TEXT'
use App\QueryTickets\PhaseOne\Qry010ActiveMembershipPlansByPrice;
use App\QueryTickets\PhaseOne\Qry011UsersWithProfiles;
use App\QueryTickets\PhaseOne\Qry012UsersWithoutProfiles;
use App\QueryTickets\PhaseOne\Qry013CustomersWithoutAccounts;
use App\QueryTickets\PhaseOne\Qry014CustomersWithUserAccounts;
use App\QueryTickets\PhaseOne\Qry015CustomersWithProfiles;
use App\QueryTickets\PhaseOne\Qry016ChildOrganizationsWithParent;
use App\QueryTickets\PhaseOne\Qry017ParentOrganizationsWithChildren;
use App\QueryTickets\PhaseOne\Qry018UsersWithOrganizations;
use App\QueryTickets\PhaseOne\Qry019UsersInMultipleOrganizations;
use App\QueryTickets\PhaseOne\Qry020MembershipsWithCustomerAndPlan;
TEXT;

$entries = <<<'TEXT'
        Qry010ActiveMembershipPlansByPrice::class,
        Qry011UsersWithProfiles::class,
        Qry012UsersWithoutProfiles::class,
        Qry013CustomersWithoutAccounts::class,
        Qry014CustomersWithUserAccounts::class,
        Qry015CustomersWithProfiles::class,
        Qry016ChildOrganizationsWithParent::class,
        Qry017ParentOrganizationsWithChildren::class,
        Qry018UsersWithOrganizations::class,
        Qry019UsersInMultipleOrganizations::class,
        Qry020MembershipsWithCustomerAndPlan::class,
TEXT;

if (str_contains($contents, 'Qry011UsersWithProfiles')) {
    fwrite(STDERR, "Registry already contains QRY-011.\n");
    exit(1);
}

if (substr_count($contents, $importMarker) !== 1) {
    fwrite(STDERR, "Expected QRY-010 import marker exactly once.\n");
    exit(1);
}

if (substr_count($contents, $listMarker) !== 1) {
    fwrite(STDERR, "Expected QRY-010 registry entry exactly once.\n");
    exit(1);
}

$contents = str_replace($importMarker, $imports."\n", $contents);
$contents = str_replace($listMarker, $entries."\n", $contents);

file_put_contents($path, $contents);
PHP

lint_targets=("${targets[@]}" "$registry")

for file in "${lint_targets[@]}"; do
    php -l "$file" > /dev/null
done

if grep -RInE ',[[:space:]]*,' \
    app/QueryTickets/PhaseOne/Qry01*.php \
    app/QueryTickets/PhaseOne/Qry020MembershipsWithCustomerAndPlan.php \
    tests/Feature/QueryTickets/PhaseOne/Qry01*Test.php \
    tests/Feature/QueryTickets/PhaseOne/Qry020MembershipsWithCustomerAndPlanTest.php; then
    echo "Found a suspicious double comma." >&2
    exit 1
fi

echo "QRY-011 through QRY-020 were created and passed PHP syntax checks."
echo
echo "Run:"
echo "  php artisan test tests/Feature/QueryTickets/PhaseOne"
echo "  php artisan test"
echo "  git diff --check"
echo "  git status --short"
