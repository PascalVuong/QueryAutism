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
            "Expected exactly one replacement in {$path}, got {$count}.\n",
        );
        exit(1);
    }

    file_put_contents($path, $updated);
}

$seeder = 'database/seeders/PhaseOneScenarioSeeder.php';

replaceOnce(
    $seeder,
    <<<'OLD'
                'status' => 'active',
                'last_login_at' => $now->subDay(),
OLD,
    <<<'NEW'
                'status' => 'active',
                'locale' => 'nl',
                'last_login_at' => $now->subDay(),
NEW,
);

replaceOnce(
    $seeder,
    <<<'OLD'
                'status' => 'active',
                'last_login_at' => $now->subDays(2),
OLD,
    <<<'NEW'
                'status' => 'active',
                'locale' => 'en',
                'last_login_at' => $now->subDays(2),
NEW,
);

replaceOnce(
    $seeder,
    <<<'OLD'
                'status' => 'active',
                'last_login_at' => $now->subDays(3),
OLD,
    <<<'NEW'
                'status' => 'active',
                'locale' => 'nl',
                'last_login_at' => $now->subDays(3),
NEW,
);

replaceOnce(
    $seeder,
    <<<'OLD'
                'name' => 'Suspended Staff',
                'email' => 'suspended@queryautism.test',
OLD,
    <<<'NEW'
                'name' => 'Suspended Staff',
                'email' => 'suspended@queryautism.test',
                'locale' => 'en',
                'last_login_at' => $now->subDays(30),
                'last_login_ip' => '192.0.2.13',
NEW,
);

replaceOnce(
    $seeder,
    <<<'OLD'
                'name' => 'Never Logged In',
                'email' => 'never.logged.in@queryautism.test',
                'status' => 'active',
            ]);

        /*
         * Organization memberships
         */
OLD,
    <<<'NEW'
                'name' => 'Never Logged In',
                'email' => 'never.logged.in@queryautism.test',
                'status' => 'active',
                'locale' => 'nl',
            ]);

        User::factory()
            ->verified()
            ->pending()
            ->create([
                'name' => 'Pending User',
                'email' => 'pending@queryautism.test',
                'locale' => 'en',
                'last_login_at' => $now->subDays(4),
                'last_login_ip' => '192.0.2.14',
            ]);

        /*
         * Organization memberships
         */
NEW,
);

replaceOnce(
    $seeder,
    <<<'OLD'
        /*
         * Membership plans
         */
OLD,
    <<<'NEW'
        Customer::query()->update([
            'marketing_consent' => false,
        ]);

        Customer::query()
            ->whereIn('customer_number', [
                'GV-0001',
                'GV-0002',
                'GV-0004',
            ])
            ->update([
                'marketing_consent' => true,
            ]);

        /*
         * Membership plans
         */
NEW,
);

replaceOnce(
    'tests/Feature/Data/PhaseOneScenarioSeederTest.php',
    <<<'OLD'
        $this->assertDatabaseCount('users', 5);
OLD,
    <<<'NEW'
        $this->assertDatabaseCount('users', 6);
NEW,
);

file_put_contents(
    'app/QueryTickets/QueryTicketRegistry.php',
    <<<'PHPFILE'
<?php

namespace App\QueryTickets;

use App\QueryTickets\PhaseOne\Qry001ActiveUsers;
use App\QueryTickets\PhaseOne\Qry002UnverifiedUsers;
use App\QueryTickets\PhaseOne\Qry003UsersWhoNeverLoggedIn;
use App\QueryTickets\PhaseOne\Qry004SuspendedUsers;
use App\QueryTickets\PhaseOne\Qry005RecentLogins;
use App\QueryTickets\PhaseOne\Qry006DutchLocaleUsers;
use App\QueryTickets\PhaseOne\Qry007ActiveOrPendingUsers;
use App\QueryTickets\PhaseOne\Qry008GuestCustomers;
use App\QueryTickets\PhaseOne\Qry009CustomersWithMarketingConsent;
use App\QueryTickets\PhaseOne\Qry010ActiveMembershipPlansByPrice;
use Illuminate\Support\Collection;

class QueryTicketRegistry
{
    /**
     * @var array<int, class-string<QueryTicket>>
     */
    private const TICKETS = [
        Qry001ActiveUsers::class,
        Qry002UnverifiedUsers::class,
        Qry003UsersWhoNeverLoggedIn::class,
        Qry004SuspendedUsers::class,
        Qry005RecentLogins::class,
        Qry006DutchLocaleUsers::class,
        Qry007ActiveOrPendingUsers::class,
        Qry008GuestCustomers::class,
        Qry009CustomersWithMarketingConsent::class,
        Qry010ActiveMembershipPlansByPrice::class,
    ];

    /**
     * @return Collection<int, QueryTicket>
     */
    public function all(): Collection
    {
        return collect(self::TICKETS)
            ->map(fn (string $ticketClass) => app($ticketClass));
    }

    public function find(string $ticketId): ?QueryTicket
    {
        return $this->all()->first(
            fn (QueryTicket $ticket) => $ticket->id() === strtoupper($ticketId),
        );
    }
}
PHPFILE,
);

$tickets = [
    'Qry002UnverifiedUsers' => [
        'id' => 'QRY-002',
        'title' => 'Unverified users',
        'description' => 'Return users whose email address has not been verified. Order them by email address.',
        'concepts' => ['select', 'whereNull', 'orderBy', 'get'],
        'columns' => ['id', 'name', 'email', 'email_verified_at', 'status'],
    ],
    'Qry003UsersWhoNeverLoggedIn' => [
        'id' => 'QRY-003',
        'title' => 'Users who never logged in',
        'description' => 'Return users without a last login timestamp. Order them by name.',
        'concepts' => ['select', 'whereNull', 'orderBy', 'get'],
        'columns' => ['id', 'name', 'email', 'last_login_at'],
    ],
    'Qry004SuspendedUsers' => [
        'id' => 'QRY-004',
        'title' => 'Suspended users',
        'description' => 'Return all suspended users ordered by email address.',
        'concepts' => ['select', 'where', 'orderBy', 'get'],
        'columns' => ['id', 'name', 'email', 'status', 'last_login_at'],
    ],
    'Qry005RecentLogins' => [
        'id' => 'QRY-005',
        'title' => 'Recent logins',
        'description' => 'Return users who logged in during the last seven days. Show the most recent login first.',
        'concepts' => ['select', 'where', 'now', 'orderByDesc', 'get'],
        'columns' => ['id', 'name', 'email', 'status', 'last_login_at'],
    ],
    'Qry006DutchLocaleUsers' => [
        'id' => 'QRY-006',
        'title' => 'Users with Dutch locale',
        'description' => 'Return users whose locale is nl. Order them by email address.',
        'concepts' => ['select', 'where', 'orderBy', 'get'],
        'columns' => ['id', 'name', 'email', 'locale'],
    ],
    'Qry007ActiveOrPendingUsers' => [
        'id' => 'QRY-007',
        'title' => 'Active or pending users',
        'description' => 'Return users whose status is active or pending. Order first by status and then by email address.',
        'concepts' => ['select', 'whereIn', 'multiple orderBy calls', 'get'],
        'columns' => ['id', 'name', 'email', 'status'],
    ],
    'Qry008GuestCustomers' => [
        'id' => 'QRY-008',
        'title' => 'Guest customers',
        'description' => 'Return customers created from the guest source. Order them by customer number.',
        'concepts' => ['select', 'where', 'orderBy', 'get'],
        'columns' => ['id', 'organization_id', 'customer_number', 'first_name', 'last_name', 'source'],
    ],
    'Qry009CustomersWithMarketingConsent' => [
        'id' => 'QRY-009',
        'title' => 'Customers with marketing consent',
        'description' => 'Return customers who consented to marketing. Order them by customer number.',
        'concepts' => ['select', 'boolean where', 'orderBy', 'get'],
        'columns' => ['id', 'organization_id', 'customer_number', 'email', 'marketing_consent'],
    ],
    'Qry010ActiveMembershipPlansByPrice' => [
        'id' => 'QRY-010',
        'title' => 'Active membership plans by price',
        'description' => 'Return active membership plans from cheapest to most expensive. Use the plan name as the secondary sort.',
        'concepts' => ['select', 'where', 'multiple orderBy calls', 'get'],
        'columns' => ['id', 'organization_id', 'code', 'name', 'status', 'price', 'currency'],
    ],
];

foreach ($tickets as $class => $ticket) {
    $concepts = implode(",\n            ", array_map(
        fn (string $concept) => var_export($concept, true).',',
        $ticket['concepts'],
    ));

    $columns = implode(",\n            ", array_map(
        fn (string $column) => var_export($column, true).',',
        $ticket['columns'],
    ));

    $contents = <<<PHPFILE
<?php

namespace App\\QueryTickets\\PhaseOne;

use App\\QueryTickets\\QueryTicket;
use Illuminate\\Support\\Collection;
use LogicException;

class {$class} extends QueryTicket
{
    public function id(): string
    {
        return '{$ticket['id']}';
    }

    public function title(): string
    {
        return '{$ticket['title']}';
    }

    public function description(): string
    {
        return '{$ticket['description']}';
    }

    public function concepts(): array
    {
        return [
            {$concepts}
        ];
    }

    public function expectedColumns(): array
    {
        return [
            {$columns}
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('{$ticket['id']} has not been solved yet.');
    }
}
PHPFILE;

    file_put_contents(
        "app/QueryTickets/PhaseOne/{$class}.php",
        $contents,
    );
}

$testFiles = [
    'tests/Feature/QueryTickets/QueryTicketTestCase.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets;

use Database\Seeders\PhaseOneScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use LogicException;
use Tests\TestCase;

abstract class QueryTicketTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * @param class-string $ticketClass
     */
    protected function runTicket(string $ticketClass): Collection
    {
        $this->seed(PhaseOneScenarioSeeder::class);

        try {
            return app($ticketClass)->run();
        } catch (LogicException $exception) {
            $this->markTestIncomplete($exception->getMessage());
        }
    }

    /**
     * @param array<int, string> $columns
     */
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
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry002UnverifiedUsersTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry002UnverifiedUsers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry002UnverifiedUsersTest extends QueryTicketTestCase
{
    public function test_it_returns_unverified_users(): void
    {
        $results = $this->runTicket(Qry002UnverifiedUsers::class);

        $this->assertSame([
            'suspended@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertTrue(
            $results->every(
                fn ($user) => is_null($user->email_verified_at),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'email_verified_at',
            'status',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry003UsersWhoNeverLoggedInTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry003UsersWhoNeverLoggedIn;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry003UsersWhoNeverLoggedInTest extends QueryTicketTestCase
{
    public function test_it_returns_users_without_a_last_login(): void
    {
        $results = $this->runTicket(
            Qry003UsersWhoNeverLoggedIn::class,
        );

        $this->assertSame([
            'never.logged.in@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertTrue(
            $results->every(
                fn ($user) => is_null($user->last_login_at),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'last_login_at',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry004SuspendedUsersTest.php' => <<<'PHPFILE'
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
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry005RecentLoginsTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry005RecentLogins;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry005RecentLoginsTest extends QueryTicketTestCase
{
    public function test_it_returns_logins_from_the_last_seven_days(): void
    {
        $results = $this->runTicket(Qry005RecentLogins::class);

        $this->assertSame([
            'owner@queryautism.test',
            'multi.manager@queryautism.test',
            'no.profile@queryautism.test',
            'pending@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertTrue(
            $results->every(
                fn ($user) => $user->last_login_at->gte(
                    now()->subDays(7),
                ),
            ),
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
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry006DutchLocaleUsersTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry006DutchLocaleUsers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry006DutchLocaleUsersTest extends QueryTicketTestCase
{
    public function test_it_returns_users_with_the_dutch_locale(): void
    {
        $results = $this->runTicket(Qry006DutchLocaleUsers::class);

        $this->assertSame([
            'never.logged.in@queryautism.test',
            'no.profile@queryautism.test',
            'owner@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertSame(
            ['nl'],
            $results->pluck('locale')->unique()->values()->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'name',
            'email',
            'locale',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry007ActiveOrPendingUsersTest.php' => <<<'PHPFILE'
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
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry008GuestCustomersTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry008GuestCustomers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry008GuestCustomersTest extends QueryTicketTestCase
{
    public function test_it_returns_guest_customers(): void
    {
        $results = $this->runTicket(Qry008GuestCustomers::class);

        $this->assertSame([
            'GV-0002',
            'GV-0003',
            'GV-0005',
            'GV-0006',
            'GV-0007',
            'RP-0003',
            'RP-0004',
        ], $results->pluck('customer_number')->all());

        $this->assertSame(
            ['guest'],
            $results->pluck('source')->unique()->values()->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'customer_number',
            'first_name',
            'last_name',
            'source',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry009CustomersWithMarketingConsentTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry009CustomersWithMarketingConsent;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry009CustomersWithMarketingConsentTest extends QueryTicketTestCase
{
    public function test_it_returns_customers_with_marketing_consent(): void
    {
        $results = $this->runTicket(
            Qry009CustomersWithMarketingConsent::class,
        );

        $this->assertSame([
            'GV-0001',
            'GV-0002',
            'GV-0004',
        ], $results->pluck('customer_number')->all());

        $this->assertTrue(
            $results->every(
                fn ($customer) => $customer->marketing_consent,
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'customer_number',
            'email',
            'marketing_consent',
        ]);
    }
}
PHPFILE,
    'tests/Feature/QueryTickets/PhaseOne/Qry010ActiveMembershipPlansByPriceTest.php' => <<<'PHPFILE'
<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry010ActiveMembershipPlansByPrice;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry010ActiveMembershipPlansByPriceTest extends QueryTicketTestCase
{
    public function test_it_returns_active_plans_from_low_to_high_price(): void
    {
        $results = $this->runTicket(
            Qry010ActiveMembershipPlansByPrice::class,
        );

        $this->assertSame([
            'GUEST',
            'PADEL',
            'STANDARD',
            'PREMIUM',
            'CORPORATE',
        ], $results->pluck('code')->all());

        $this->assertSame(
            ['active'],
            $results->pluck('status')->unique()->values()->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'code',
            'name',
            'status',
            'price',
            'currency',
        ]);
    }
}
PHPFILE,
];

foreach ($testFiles as $path => $contents) {
    $directory = dirname($path);

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    file_put_contents($path, $contents);
}

echo "QRY-002 through QRY-010 exercises and tests created.\n";
PHP

echo
echo "Run:"
echo "  php artisan test tests/Feature/QueryTickets/PhaseOne"
echo "  php artisan test"
echo "  git status --short"
