#!/usr/bin/env bash
set -euo pipefail

if [[ ! -f artisan ]]; then
    echo "Run this script from the QueryAutism project root." >&2
    exit 1
fi

php <<'PHP'
<?php

function replaceOnce(string $path, string $old, string $new): void
{
    $contents = file_get_contents($path);

    if (!str_contains($contents, $old)) {
        fwrite(STDERR, "Expected block not found in {$path}.\n");
        exit(1);
    }

    $updated = str_replace($old, $new, $contents, $count);

    if ($count !== 1) {
        fwrite(
            STDERR,
            "Expected one replacement in {$path}, got {$count}.\n",
        );
        exit(1);
    }

    file_put_contents($path, $updated);
}

$files = [
    'app/QueryTickets/PhaseOne/Qry041MembershipCountByStatus.php' => <<<'PHPFILE'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry041MembershipCountByStatus extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-041';
    }

    public function title(): string
    {
        return 'Membership count per status';
    }

    public function description(): string
    {
        return 'Group memberships by status and return the number of '
            .'memberships per status. Order the rows by status.';
    }

    public function concepts(): array
    {
        return [
            'selectRaw',
            'count',
            'groupBy',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'status',
            'membership_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-041 has not been solved yet.');
    }
}
PHPFILE,
    'app/QueryTickets/PhaseOne/Qry042AveragePlanPriceByOrganization.php' => <<<'PHPFILE'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry042AveragePlanPriceByOrganization extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-042';
    }

    public function title(): string
    {
        return 'Average plan price per organization';
    }

    public function description(): string
    {
        return 'Return every organization with the average price of its '
            .'membership plans. Organizations without plans must remain in '
            .'the result. Order by organization name.';
    }

    public function concepts(): array
    {
        return [
            'withAvg',
            'aggregate relationship',
            'select',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'membership_plans_avg_price',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-042 has not been solved yet.');
    }
}
PHPFILE,
    'app/QueryTickets/PhaseOne/Qry043MembershipRevenueByOrganization.php' => <<<'PHPFILE'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry043MembershipRevenueByOrganization extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-043';
    }

    public function title(): string
    {
        return 'Membership revenue per organization';
    }

    public function description(): string
    {
        return 'Return every organization with the sum of agreed_price from '
            .'its memberships. Organizations without memberships must remain '
            .'in the result. Order by organization name.';
    }

    public function concepts(): array
    {
        return [
            'withSum',
            'aggregate relationship',
            'select',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'memberships_sum_agreed_price',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-043 has not been solved yet.');
    }
}
PHPFILE,
    'app/QueryTickets/PhaseOne/Qry044MostExpensivePlanPerOrganization.php' => <<<'PHPFILE'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry044MostExpensivePlanPerOrganization extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-044';
    }

    public function title(): string
    {
        return 'Most expensive plan per organization';
    }

    public function description(): string
    {
        return 'Return the most expensive membership plan for each '
            .'organization that has plans. Select only the requested plan '
            .'columns and order by organization_id.';
    }

    public function concepts(): array
    {
        return [
            'correlated subquery',
            'max',
            'whereColumn',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'code',
            'name',
            'price',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-044 has not been solved yet.');
    }
}
PHPFILE,
    'app/QueryTickets/PhaseOne/Qry045OrganizationWithMostCustomers.php' => <<<'PHPFILE'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry045OrganizationWithMostCustomers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-045';
    }

    public function title(): string
    {
        return 'Organization with most customers';
    }

    public function description(): string
    {
        return 'Return only the organization with the highest number of '
            .'customers. Include customers_count and select only the '
            .'requested columns.';
    }

    public function concepts(): array
    {
        return [
            'withCount',
            'orderByDesc',
            'limit',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'customers_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-045 has not been solved yet.');
    }
}
PHPFILE,
    'app/QueryTickets/PhaseOne/Qry046CustomerWithMostMemberships.php' => <<<'PHPFILE'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry046CustomerWithMostMemberships extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-046';
    }

    public function title(): string
    {
        return 'Customer with most memberships';
    }

    public function description(): string
    {
        return 'Return only the customer with the highest number of '
            .'memberships. Include memberships_count and select only the '
            .'requested columns.';
    }

    public function concepts(): array
    {
        return [
            'withCount',
            'orderByDesc',
            'limit',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'customer_number',
            'first_name',
            'last_name',
            'memberships_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-046 has not been solved yet.');
    }
}
PHPFILE,
    'app/QueryTickets/PhaseOne/Qry047DuplicateExternalIdentifiers.php' => <<<'PHPFILE'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry047DuplicateExternalIdentifiers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-047';
    }

    public function title(): string
    {
        return 'Duplicate external identifiers';
    }

    public function description(): string
    {
        return 'Find provider, identifier_type and normalized_value '
            .'combinations that occur more than once. Return their duplicate '
            .'count and order by provider, identifier_type and normalized_value.';
    }

    public function concepts(): array
    {
        return [
            'groupBy',
            'count',
            'having',
            'multiple orderBy calls',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'provider',
            'identifier_type',
            'normalized_value',
            'duplicate_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-047 has not been solved yet.');
    }
}
PHPFILE,
    'app/QueryTickets/PhaseOne/Qry048CrossOrganizationVerifiedIdentifiers.php' => <<<'PHPFILE'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry048CrossOrganizationVerifiedIdentifiers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-048';
    }

    public function title(): string
    {
        return 'Cross-organization verified identifiers';
    }

    public function description(): string
    {
        return 'Find verified external identifiers whose provider, type and '
            .'normalized value occur in more than one organization. Return '
            .'both the organization count and identifier count.';
    }

    public function concepts(): array
    {
        return [
            'whereNotNull',
            'count distinct',
            'groupBy',
            'havingRaw',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'provider',
            'identifier_type',
            'normalized_value',
            'organization_count',
            'identifier_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-048 has not been solved yet.');
    }
}
PHPFILE,
    'app/QueryTickets/PhaseOne/Qry049CustomerMembershipReport.php' => <<<'PHPFILE'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry049CustomerMembershipReport extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-049';
    }

    public function title(): string
    {
        return 'Customer membership report';
    }

    public function description(): string
    {
        return 'Build a report by joining memberships, customers, '
            .'organizations and membership plans. Return the requested '
            .'aliases and order by organization name, customer number and '
            .'membership number.';
    }

    public function concepts(): array
    {
        return [
            'join',
            'column aliases',
            'report query',
            'multiple orderBy calls',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'organization_name',
            'customer_number',
            'first_name',
            'last_name',
            'membership_number',
            'plan_name',
            'membership_status',
            'agreed_price',
            'ends_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-049 has not been solved yet.');
    }
}
PHPFILE,
    'app/QueryTickets/PhaseOne/Qry050OrganizationHealthReport.php' => <<<'PHPFILE'
<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry050OrganizationHealthReport extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-050';
    }

    public function title(): string
    {
        return 'Organization health report';
    }

    public function description(): string
    {
        return 'Return one row per organization with customer_count, '
            .'active_membership_count, expired_membership_count, '
            .'membership_revenue, blocked_customer_count and '
            .'latest_membership_updated_at. Numeric metrics must be zero '
            .'when no related rows exist. Order by organization name.';
    }

    public function concepts(): array
    {
        return [
            'conditional aggregates',
            'correlated subqueries',
            'coalesce',
            'report query',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'customer_count',
            'active_membership_count',
            'expired_membership_count',
            'membership_revenue',
            'blocked_customer_count',
            'latest_membership_updated_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-050 has not been solved yet.');
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry041MembershipCountByStatusTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry041MembershipCountByStatus;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry041MembershipCountByStatusTest extends QueryTicketTestCase
{
    public function test_it_counts_memberships_per_status(): void
    {
        $results = $this->runTicket(
            Qry041MembershipCountByStatus::class,
        );

        $this->assertSame([
            ['status' => 'active', 'membership_count' => 5],
            ['status' => 'cancelled', 'membership_count' => 1],
            ['status' => 'expired', 'membership_count' => 1],
            ['status' => 'paused', 'membership_count' => 1],
        ], $results->map(fn ($row) => [
            'status' => $row->status,
            'membership_count' => (int) $row->membership_count,
        ])->all());

        $this->assertResultColumns($results, [
            'status',
            'membership_count',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry042AveragePlanPriceByOrganizationTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry042AveragePlanPriceByOrganization;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry042AveragePlanPriceByOrganizationTest extends QueryTicketTestCase
{
    public function test_it_returns_average_plan_price_per_organization(): void
    {
        $results = $this->runTicket(
            Qry042AveragePlanPriceByOrganization::class,
        );

        $this->assertSame([
            ['name' => 'Dormant Event Hall', 'average_price' => null],
            ['name' => 'Green Valley Golf Club', 'average_price' => 198.75],
            ['name' => 'Rotterdam Padel Centre', 'average_price' => 467.5],
            ['name' => 'Serenity Wellness', 'average_price' => 75.0],
            ['name' => 'VenueOps Leisure Group', 'average_price' => null],
        ], $results->map(fn ($organization) => [
            'name' => $organization->name,
            'average_price' => is_null(
                $organization->membership_plans_avg_price,
            )
                ? null
                : round(
                    (float) $organization->membership_plans_avg_price,
                    2,
                ),
        ])->all());

        $this->assertResultColumns($results, [
            'id',
            'name',
            'membership_plans_avg_price',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry043MembershipRevenueByOrganizationTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry043MembershipRevenueByOrganization;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry043MembershipRevenueByOrganizationTest extends QueryTicketTestCase
{
    public function test_it_returns_membership_revenue_per_organization(): void
    {
        $results = $this->runTicket(
            Qry043MembershipRevenueByOrganization::class,
        );

        $this->assertSame([
            ['name' => 'Dormant Event Hall', 'revenue' => null],
            ['name' => 'Green Valley Golf Club', 'revenue' => 955.0],
            ['name' => 'Rotterdam Padel Centre', 'revenue' => 885.0],
            ['name' => 'Serenity Wellness', 'revenue' => null],
            ['name' => 'VenueOps Leisure Group', 'revenue' => null],
        ], $results->map(fn ($organization) => [
            'name' => $organization->name,
            'revenue' => is_null(
                $organization->memberships_sum_agreed_price,
            )
                ? null
                : round(
                    (float) $organization->memberships_sum_agreed_price,
                    2,
                ),
        ])->all());

        $this->assertResultColumns($results, [
            'id',
            'name',
            'memberships_sum_agreed_price',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry044MostExpensivePlanPerOrganizationTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry044MostExpensivePlanPerOrganization;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry044MostExpensivePlanPerOrganizationTest extends QueryTicketTestCase
{
    public function test_it_returns_the_most_expensive_plan_per_organization(): void
    {
        $results = $this->runTicket(
            Qry044MostExpensivePlanPerOrganization::class,
        );

        $this->assertSame([
            ['code' => 'PREMIUM', 'price' => 450.0],
            ['code' => 'CORPORATE', 'price' => 900.0],
            ['code' => 'WELLNESS', 'price' => 75.0],
        ], $results->map(fn ($plan) => [
            'code' => $plan->code,
            'price' => round((float) $plan->price, 2),
        ])->all());

        $this->assertResultColumns($results, [
            'id',
            'organization_id',
            'code',
            'name',
            'price',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry045OrganizationWithMostCustomersTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry045OrganizationWithMostCustomers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry045OrganizationWithMostCustomersTest extends QueryTicketTestCase
{
    public function test_it_returns_the_organization_with_most_customers(): void
    {
        $results = $this->runTicket(
            Qry045OrganizationWithMostCustomers::class,
        );

        $this->assertCount(1, $results);
        $this->assertSame(
            'Green Valley Golf Club',
            $results->first()->name,
        );
        $this->assertSame(7, (int) $results->first()->customers_count);

        $this->assertResultColumns($results, [
            'id',
            'name',
            'customers_count',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry046CustomerWithMostMembershipsTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry046CustomerWithMostMemberships;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry046CustomerWithMostMembershipsTest extends QueryTicketTestCase
{
    public function test_it_returns_the_customer_with_most_memberships(): void
    {
        $results = $this->runTicket(
            Qry046CustomerWithMostMemberships::class,
        );

        $this->assertCount(1, $results);
        $this->assertSame(
            'GV-0005',
            $results->first()->customer_number,
        );
        $this->assertSame(
            2,
            (int) $results->first()->memberships_count,
        );

        $this->assertResultColumns($results, [
            'id',
            'customer_number',
            'first_name',
            'last_name',
            'memberships_count',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry047DuplicateExternalIdentifiersTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry047DuplicateExternalIdentifiers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry047DuplicateExternalIdentifiersTest extends QueryTicketTestCase
{
    public function test_it_returns_duplicate_external_identifiers(): void
    {
        $results = $this->runTicket(
            Qry047DuplicateExternalIdentifiers::class,
        );

        $this->assertSame([
            [
                'provider' => 'booking_partner',
                'identifier_type' => 'customer_number',
                'normalized_value' => 'DUP-2000',
                'duplicate_count' => 2,
            ],
            [
                'provider' => 'golf_federation',
                'identifier_type' => 'membership_number',
                'normalized_value' => 'GVF-1001',
                'duplicate_count' => 2,
            ],
        ], $results->map(fn ($row) => [
            'provider' => $row->provider,
            'identifier_type' => $row->identifier_type,
            'normalized_value' => $row->normalized_value,
            'duplicate_count' => (int) $row->duplicate_count,
        ])->all());

        $this->assertResultColumns($results, [
            'provider',
            'identifier_type',
            'normalized_value',
            'duplicate_count',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry048CrossOrganizationVerifiedIdentifiersTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry048CrossOrganizationVerifiedIdentifiers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry048CrossOrganizationVerifiedIdentifiersTest extends QueryTicketTestCase
{
    public function test_it_returns_verified_identifiers_used_by_multiple_organizations(): void
    {
        $results = $this->runTicket(
            Qry048CrossOrganizationVerifiedIdentifiers::class,
        );

        $this->assertSame([
            [
                'provider' => 'golf_federation',
                'identifier_type' => 'membership_number',
                'normalized_value' => 'GVF-1001',
                'organization_count' => 2,
                'identifier_count' => 2,
            ],
        ], $results->map(fn ($row) => [
            'provider' => $row->provider,
            'identifier_type' => $row->identifier_type,
            'normalized_value' => $row->normalized_value,
            'organization_count' => (int) $row->organization_count,
            'identifier_count' => (int) $row->identifier_count,
        ])->all());

        $this->assertResultColumns($results, [
            'provider',
            'identifier_type',
            'normalized_value',
            'organization_count',
            'identifier_count',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry049CustomerMembershipReportTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry049CustomerMembershipReport;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry049CustomerMembershipReportTest extends QueryTicketTestCase
{
    public function test_it_returns_the_customer_membership_report(): void
    {
        $results = $this->runTicket(
            Qry049CustomerMembershipReport::class,
        );

        $this->assertSame([
            'GV-MEM-0001',
            'GV-MEM-0002',
            'GV-MEM-0003',
            'GV-MEM-0004',
            'GV-MEM-0005',
            'GV-MEM-0006',
            'RP-MEM-0001',
            'RP-MEM-0002',
        ], $results->pluck('membership_number')->all());

        $this->assertSame([
            'Green Valley Golf Club',
            'Green Valley Golf Club',
            'Green Valley Golf Club',
            'Green Valley Golf Club',
            'Green Valley Golf Club',
            'Green Valley Golf Club',
            'Rotterdam Padel Centre',
            'Rotterdam Padel Centre',
        ], $results->pluck('organization_name')->all());

        $this->assertSame([
            425.0,
            0.0,
            45.0,
            40.0,
            45.0,
            400.0,
            35.0,
            850.0,
        ], $results->map(
            fn ($row) => round((float) $row->agreed_price, 2),
        )->all());

        $this->assertResultColumns($results, [
            'organization_name',
            'customer_number',
            'first_name',
            'last_name',
            'membership_number',
            'plan_name',
            'membership_status',
            'agreed_price',
            'ends_at',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry050OrganizationHealthReportTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\Models\Membership;
use App\QueryTickets\PhaseOne\Qry050OrganizationHealthReport;
use Carbon\Carbon;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry050OrganizationHealthReportTest extends QueryTicketTestCase
{
    public function test_it_returns_the_organization_health_report(): void
    {
        $results = $this->runTicket(
            Qry050OrganizationHealthReport::class,
        );

        $this->assertSame([
            [
                'name' => 'Dormant Event Hall',
                'customer_count' => 0,
                'active_membership_count' => 0,
                'expired_membership_count' => 0,
                'membership_revenue' => 0.0,
                'blocked_customer_count' => 0,
            ],
            [
                'name' => 'Green Valley Golf Club',
                'customer_count' => 7,
                'active_membership_count' => 3,
                'expired_membership_count' => 1,
                'membership_revenue' => 955.0,
                'blocked_customer_count' => 1,
            ],
            [
                'name' => 'Rotterdam Padel Centre',
                'customer_count' => 4,
                'active_membership_count' => 2,
                'expired_membership_count' => 0,
                'membership_revenue' => 885.0,
                'blocked_customer_count' => 0,
            ],
            [
                'name' => 'Serenity Wellness',
                'customer_count' => 0,
                'active_membership_count' => 0,
                'expired_membership_count' => 0,
                'membership_revenue' => 0.0,
                'blocked_customer_count' => 0,
            ],
            [
                'name' => 'VenueOps Leisure Group',
                'customer_count' => 0,
                'active_membership_count' => 0,
                'expired_membership_count' => 0,
                'membership_revenue' => 0.0,
                'blocked_customer_count' => 0,
            ],
        ], $results->map(fn ($row) => [
            'name' => $row->name,
            'customer_count' => (int) $row->customer_count,
            'active_membership_count' => (int) $row->active_membership_count,
            'expired_membership_count' => (int) $row->expired_membership_count,
            'membership_revenue' => round(
                (float) $row->membership_revenue,
                2,
            ),
            'blocked_customer_count' => (int) $row->blocked_customer_count,
        ])->all());

        foreach ($results as $row) {
            $expectedLatest = Membership::query()
                ->where('organization_id', $row->id)
                ->max('updated_at');

            $actualLatest = $row->latest_membership_updated_at;

            if (is_null($expectedLatest)) {
                $this->assertNull($actualLatest);

                continue;
            }

            $this->assertTrue(
                Carbon::parse($expectedLatest)->equalTo(
                    Carbon::parse($actualLatest),
                ),
            );
        }

        $this->assertResultColumns($results, [
            'id',
            'name',
            'customer_count',
            'active_membership_count',
            'expired_membership_count',
            'membership_revenue',
            'blocked_customer_count',
            'latest_membership_updated_at',
        ]);
    }
}
PHPFILE,
];

foreach (array_keys($files) as $path) {
    if (file_exists($path)) {
        fwrite(STDERR, "Refusing to overwrite existing file: {$path}\n");
        exit(1);
    }
}

replaceOnce(
    'app/QueryTickets/QueryTicketRegistry.php',
    <<<'OLD'
use Illuminate\Support\Collection;
OLD,
    <<<'NEW'
use App\QueryTickets\PhaseOne\Qry041MembershipCountByStatus;
use App\QueryTickets\PhaseOne\Qry042AveragePlanPriceByOrganization;
use App\QueryTickets\PhaseOne\Qry043MembershipRevenueByOrganization;
use App\QueryTickets\PhaseOne\Qry044MostExpensivePlanPerOrganization;
use App\QueryTickets\PhaseOne\Qry045OrganizationWithMostCustomers;
use App\QueryTickets\PhaseOne\Qry046CustomerWithMostMemberships;
use App\QueryTickets\PhaseOne\Qry047DuplicateExternalIdentifiers;
use App\QueryTickets\PhaseOne\Qry048CrossOrganizationVerifiedIdentifiers;
use App\QueryTickets\PhaseOne\Qry049CustomerMembershipReport;
use App\QueryTickets\PhaseOne\Qry050OrganizationHealthReport;
use Illuminate\Support\Collection;
NEW,
);

replaceOnce(
    'app/QueryTickets/QueryTicketRegistry.php',
    <<<'OLD'
        Qry040OverlappingMemberships::class,
    ];
OLD,
    <<<'NEW'
        Qry040OverlappingMemberships::class,
        Qry041MembershipCountByStatus::class,
        Qry042AveragePlanPriceByOrganization::class,
        Qry043MembershipRevenueByOrganization::class,
        Qry044MostExpensivePlanPerOrganization::class,
        Qry045OrganizationWithMostCustomers::class,
        Qry046CustomerWithMostMemberships::class,
        Qry047DuplicateExternalIdentifiers::class,
        Qry048CrossOrganizationVerifiedIdentifiers::class,
        Qry049CustomerMembershipReport::class,
        Qry050OrganizationHealthReport::class,
    ];
NEW,
);

replaceOnce(
    'tests/Feature/QueryTickets/QueryTicketTestCase.php',
    <<<'OLD'
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
OLD,
    <<<'NEW'
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
NEW,
);

replaceOnce(
    'tests/Feature/QueryTickets/QueryTicketTestCase.php',
    <<<'OLD'
    protected function assertExactColumns(
        Collection $results,
        array $columns,
    ): void {
        foreach ($results as $result) {
            $this->assertSame(
                $columns,
                array_keys($result->getAttributes()),
            );
        }
    }
}
OLD,
    <<<'NEW'
    protected function assertExactColumns(
        Collection $results,
        array $columns,
    ): void {
        foreach ($results as $result) {
            $this->assertSame(
                $columns,
                array_keys($result->getAttributes()),
            );
        }
    }

    /**
     * @param array<int, string> $columns
     */
    protected function assertResultColumns(
        Collection $results,
        array $columns,
    ): void {
        foreach ($results as $result) {
            $attributes = $result instanceof Model
                ? $result->getAttributes()
                : get_object_vars($result);

            $this->assertSame(
                $columns,
                array_keys($attributes),
            );
        }
    }
}
NEW,
);

foreach ($files as $path => $contents) {
    $directory = dirname($path);

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    file_put_contents($path, $contents);
}

echo "QRY-041 through QRY-050 exercises and tests created.\n";
PHP

created_files=(
    'app/QueryTickets/PhaseOne/Qry041MembershipCountByStatus.php'
    'app/QueryTickets/PhaseOne/Qry042AveragePlanPriceByOrganization.php'
    'app/QueryTickets/PhaseOne/Qry043MembershipRevenueByOrganization.php'
    'app/QueryTickets/PhaseOne/Qry044MostExpensivePlanPerOrganization.php'
    'app/QueryTickets/PhaseOne/Qry045OrganizationWithMostCustomers.php'
    'app/QueryTickets/PhaseOne/Qry046CustomerWithMostMemberships.php'
    'app/QueryTickets/PhaseOne/Qry047DuplicateExternalIdentifiers.php'
    'app/QueryTickets/PhaseOne/Qry048CrossOrganizationVerifiedIdentifiers.php'
    'app/QueryTickets/PhaseOne/Qry049CustomerMembershipReport.php'
    'app/QueryTickets/PhaseOne/Qry050OrganizationHealthReport.php'
    'tests/Feature/QueryTickets/PhaseOne/Qry041MembershipCountByStatusTest.php'
    'tests/Feature/QueryTickets/PhaseOne/Qry042AveragePlanPriceByOrganizationTest.php'
    'tests/Feature/QueryTickets/PhaseOne/Qry043MembershipRevenueByOrganizationTest.php'
    'tests/Feature/QueryTickets/PhaseOne/Qry044MostExpensivePlanPerOrganizationTest.php'
    'tests/Feature/QueryTickets/PhaseOne/Qry045OrganizationWithMostCustomersTest.php'
    'tests/Feature/QueryTickets/PhaseOne/Qry046CustomerWithMostMembershipsTest.php'
    'tests/Feature/QueryTickets/PhaseOne/Qry047DuplicateExternalIdentifiersTest.php'
    'tests/Feature/QueryTickets/PhaseOne/Qry048CrossOrganizationVerifiedIdentifiersTest.php'
    'tests/Feature/QueryTickets/PhaseOne/Qry049CustomerMembershipReportTest.php'
    'tests/Feature/QueryTickets/PhaseOne/Qry050OrganizationHealthReportTest.php'
)

for file in "${created_files[@]}"; do
    php -l "$file" >/dev/null
done

php -l app/QueryTickets/QueryTicketRegistry.php >/dev/null
php -l tests/Feature/QueryTickets/QueryTicketTestCase.php >/dev/null

if grep -RInE ',[[:space:]]*,' \
    app/QueryTickets/PhaseOne/Qry04*.php \
    app/QueryTickets/PhaseOne/Qry050*.php \
    tests/Feature/QueryTickets/PhaseOne/Qry04*.php \
    tests/Feature/QueryTickets/PhaseOne/Qry050*.php
then
    echo "Possible empty array element found." >&2
    exit 1
fi

echo
echo "Syntax checks passed."
echo
echo "Run:"
echo "  php artisan test tests/Feature/QueryTickets/PhaseOne"
echo "  php artisan test"
echo "  git diff --check"
echo "  git status --short"
