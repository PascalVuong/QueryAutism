# QueryAutism

[Lees deze README in het Nederlands](README-NL.md)

QueryAutism is a Laravel and PostgreSQL learning project for practicing database queries step by step.

The repository contains a realistic multi-tenant SaaS dataset with organizations, customers, memberships, reservations, payments, inventory, and sales orders. Every exercise has its own Query Ticket and an automated test that checks whether the result is exactly correct.

## What will you learn?

The exercises gradually progress from simple Eloquent queries to advanced reports.

Topics include:

- filtering, sorting, and selecting columns;
- Eloquent relationships and eager loading;
- `whereHas`, `doesntHave`, `withCount`, and `withSum`;
- joins and subqueries;
- `GROUP BY`, `HAVING`, `COUNT`, `SUM`, and `AVG`;
- calculated columns and reconciliation queries;
- PostgreSQL window functions such as `SUM OVER` and `PARTITION BY`;
- reports across multiple organizations and tables.

You may solve a ticket with Eloquent, the Query Builder, or raw SQL, as long as the result satisfies the exercise contract.

## Project structure

The learning environment contains four phases and 120 Query Tickets.

| Phase | Tickets | Topic |
|---|---:|---|
| Phase One | QRY-001 through QRY-050 | Identity & Memberships |
| Phase Two | QRY-051 through QRY-075 | Reservations & Resources |
| Phase Three | QRY-076 through QRY-095 | Pricing & Payments |
| Phase Four | QRY-096 through QRY-120 | Products, Inventory & Orders |

The seeders always create the same dataset. This keeps the test results predictable and allows you to run a query repeatedly without changing the expected outcome.

## Technical requirements

- PHP 8.4 or higher
- Composer
- PostgreSQL 17
- Laravel 13
- Git

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/PascalVuong/QueryAutism.git
cd QueryAutism
```

### 2. Install the PHP dependencies

```bash
composer install
```

### 3. Create the environment file

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Create the PostgreSQL databases

Create one database for normal local development and a separate database for automated tests:

```bash
createdb query_autism
createdb query_autism_testing
```

Each database has a different purpose:

```text
query_autism
└── normal local database used by the application

query_autism_testing
└── temporary database used by automated tests
```

Update the database settings in `.env`:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=query_autism
DB_USERNAME=postgres
DB_PASSWORD=
```

Use your own PostgreSQL username and password when they differ from this example.

### 5. Configure the test environment

Create a separate environment file for tests:

```bash
cp .env .env.testing
```

At minimum, update these values in `.env.testing`:

```dotenv
APP_ENV=testing
DB_DATABASE=query_autism_testing
```

#### Why is a separate test database required?

The tests use Laravel's `RefreshDatabase` functionality. During testing, Laravel may:

- remove or rebuild tables;
- run the migrations again;
- execute the fixed scenario seeders;
- start every test with a clean database.

If the tests used the same database as the normal application, your local data could be deleted.

Separate databases keep your normal database safe:

```text
.env
└── DB_DATABASE=query_autism

.env.testing
└── DB_DATABASE=query_autism_testing
```

When you run:

```bash
php artisan test
```

Laravel automatically uses the settings from `.env.testing`.

> **Warning:** never use the same `DB_DATABASE` value in `.env.testing` and `.env`.

### 6. Build and seed the database

```bash
php artisan migrate:fresh --seed
```

This recreates all tables and runs the four fixed scenario seeders.

> `migrate:fresh` deletes all existing tables from the configured database first. Always verify that `.env` points to the correct local database before running it.

### 7. Start the application

```bash
php artisan serve
```

Then open:

```text
http://127.0.0.1:8000/queries
```

## How does a Query Ticket work?

Every exercise is stored in its own class under:

```text
app/QueryTickets/
```

Example:

```text
app/QueryTickets/PhaseOne/Qry001ActiveUsers.php
```

A Query Ticket contains information such as:

```php
public function id(): string
{
    return 'QRY-001';
}

public function title(): string
{
    return 'Active users';
}

public function description(): string
{
    return 'Return all active users.';
}

public function concepts(): array
{
    return [
        'where',
        'select',
        'orderBy',
    ];
}

public function expectedColumns(): array
{
    return [
        'id',
        'name',
        'email',
    ];
}

public function run(): Collection
{
    throw new LogicException('QRY-001 has not been solved yet.');
}
```

Your task is to replace only the contents of `run()` with a query that returns the requested result.

## Recommended exercise workflow

### 1. Choose a ticket

Open the dashboard and start with the first unsolved ticket:

```text
http://127.0.0.1:8000/queries
```

Work through the tickets in order when possible. Later tickets build on techniques introduced in earlier exercises.

### 2. Read the contract

Check the following in the ticket class:

- the description;
- the listed concepts;
- the expected columns;
- the required sorting.

Then open the matching test under:

```text
tests/Feature/QueryTickets/
```

The test shows exactly which records, columns, relationships, and ordering are expected.

### 3. Write the query

Replace the temporary exception in `run()`:

```php
throw new LogicException('QRY-001 has not been solved yet.');
```

with your query:

```php
public function run(): Collection
{
    return User::query()
        ->where('status', 'active')
        ->orderBy('email')
        ->get([
            'id',
            'name',
            'email',
        ]);
}
```

This is only an example of the expected structure. Use the tables, relationships, filters, and columns required by each ticket.

### 4. Run only the test for the ticket

Run the full test file:

```bash
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry001ActiveUsersTest.php
```

Or use a filter:

```bash
php artisan test --filter=Qry001ActiveUsersTest
```

### 5. Interpret the result

A ticket can have three states:

| Status | Meaning |
|---|---|
| Incomplete | `run()` still contains the temporary `LogicException` |
| Failed | A query exists, but the result is not exactly correct yet |
| Passed | The query returns exactly the expected result |

An incorrect query is therefore not marked as incomplete. The test fails and shows which part is incorrect.

### 6. Run the full test suite

When the ticket passes:

```bash
php artisan test
```

This confirms that your solution did not break another exercise or project component.

### 7. Commit your progress

```bash
git add app/QueryTickets
git commit -m "Solve QRY-001 active users"
```

## Important exercise rules

### Do not change the tests to make a query pass

The tests are the contract of the exercise. Change your query, not the expected result.

### Do not change the scenario seeders while solving tickets

The fixed dataset keeps every exercise reproducible.

Rebuild the database with:

```bash
php artisan migrate:fresh --seed
```

### Return only the requested columns

Many tests check the columns exactly:

```php
->get([
    'id',
    'name',
    'email',
]);
```

Using `select('*')` or returning a model with extra attributes can therefore correctly cause a test failure.

### Keep foreign keys when eager loading

When limiting selected columns, include the foreign key Eloquent needs to connect the relationship.

Example:

```php
Product::query()
    ->with('category:id,name')
    ->get([
        'id',
        'product_category_id',
        'code',
        'name',
    ]);
```

Without `product_category_id`, Eloquent cannot attach the category to the product.

### Follow the required ordering

The tests often check both the records and their order. Add the requested `orderBy()` clauses.

## Useful commands

Rebuild the database:

```bash
php artisan migrate:fresh --seed
```

Run all data tests:

```bash
php artisan test tests/Feature/Data
```

Run all Query Tickets for one phase:

```bash
php artisan test tests/Feature/QueryTickets/PhaseOne
php artisan test tests/Feature/QueryTickets/PhaseTwo
php artisan test tests/Feature/QueryTickets/PhaseThree
php artisan test tests/Feature/QueryTickets/PhaseFour
```

Run the full test suite:

```bash
php artisan test
```

List the available routes:

```bash
php artisan route:list
```

## Test commands for QRY-001 through QRY-120

Every ticket below includes both available commands: a short `--filter` command and the command for the exact test file. The ticket names match the names shown in the QueryAutism dashboard.

Laravel uses `.env.testing` for these commands, so the tests run against the separate test database instead of your normal local database.

### Run multiple tests

```bash
# Run all Query Ticket tests
php artisan test tests/Feature/QueryTickets

# Run all data, factory, relationship, and seeder tests
php artisan test tests/Feature/Data

# Run the complete project test suite
php artisan test
```

### Phase One — QRY-001 through QRY-050

```bash
# Run all tests in this phase
php artisan test tests/Feature/QueryTickets/PhaseOne
```

#### QRY-001 — Active users ordered by latest login

```bash
# Run by test class
php artisan test --filter=Qry001ActiveUsersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry001ActiveUsersTest.php
```

#### QRY-002 — Unverified users

```bash
# Run by test class
php artisan test --filter=Qry002UnverifiedUsersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry002UnverifiedUsersTest.php
```

#### QRY-003 — Users who never logged in

```bash
# Run by test class
php artisan test --filter=Qry003UsersWhoNeverLoggedInTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry003UsersWhoNeverLoggedInTest.php
```

#### QRY-004 — Suspended users

```bash
# Run by test class
php artisan test --filter=Qry004SuspendedUsersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry004SuspendedUsersTest.php
```

#### QRY-005 — Recent logins

```bash
# Run by test class
php artisan test --filter=Qry005RecentLoginsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry005RecentLoginsTest.php
```

#### QRY-006 — Users with Dutch locale

```bash
# Run by test class
php artisan test --filter=Qry006DutchLocaleUsersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry006DutchLocaleUsersTest.php
```

#### QRY-007 — Active or pending users

```bash
# Run by test class
php artisan test --filter=Qry007ActiveOrPendingUsersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry007ActiveOrPendingUsersTest.php
```

#### QRY-008 — Guest customers

```bash
# Run by test class
php artisan test --filter=Qry008GuestCustomersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry008GuestCustomersTest.php
```

#### QRY-009 — Customers with marketing consent

```bash
# Run by test class
php artisan test --filter=Qry009CustomersWithMarketingConsentTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry009CustomersWithMarketingConsentTest.php
```

#### QRY-010 — Active membership plans by price

```bash
# Run by test class
php artisan test --filter=Qry010ActiveMembershipPlansByPriceTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry010ActiveMembershipPlansByPriceTest.php
```

#### QRY-011 — Users with profiles

```bash
# Run by test class
php artisan test --filter=Qry011UsersWithProfilesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry011UsersWithProfilesTest.php
```

#### QRY-012 — Users without profiles

```bash
# Run by test class
php artisan test --filter=Qry012UsersWithoutProfilesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry012UsersWithoutProfilesTest.php
```

#### QRY-013 — Customers without user accounts

```bash
# Run by test class
php artisan test --filter=Qry013CustomersWithoutAccountsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry013CustomersWithoutAccountsTest.php
```

#### QRY-014 — Customers with user accounts

```bash
# Run by test class
php artisan test --filter=Qry014CustomersWithUserAccountsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry014CustomersWithUserAccountsTest.php
```

#### QRY-015 — Customers with profiles

```bash
# Run by test class
php artisan test --filter=Qry015CustomersWithProfilesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry015CustomersWithProfilesTest.php
```

#### QRY-016 — Child organizations with parent

```bash
# Run by test class
php artisan test --filter=Qry016ChildOrganizationsWithParentTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry016ChildOrganizationsWithParentTest.php
```

#### QRY-017 — Parent organizations with children

```bash
# Run by test class
php artisan test --filter=Qry017ParentOrganizationsWithChildrenTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry017ParentOrganizationsWithChildrenTest.php
```

#### QRY-018 — Users with organizations

```bash
# Run by test class
php artisan test --filter=Qry018UsersWithOrganizationsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry018UsersWithOrganizationsTest.php
```

#### QRY-019 — Users in multiple organizations

```bash
# Run by test class
php artisan test --filter=Qry019UsersInMultipleOrganizationsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry019UsersInMultipleOrganizationsTest.php
```

#### QRY-020 — Memberships with customer and plan

```bash
# Run by test class
php artisan test --filter=Qry020MembershipsWithCustomerAndPlanTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry020MembershipsWithCustomerAndPlanTest.php
```

#### QRY-021 — Organizations with customers

```bash
# Run by test class
php artisan test --filter=Qry021OrganizationsWithCustomersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry021OrganizationsWithCustomersTest.php
```

#### QRY-022 — Organizations without customers

```bash
# Run by test class
php artisan test --filter=Qry022OrganizationsWithoutCustomersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry022OrganizationsWithoutCustomersTest.php
```

#### QRY-023 — Customers without memberships

```bash
# Run by test class
php artisan test --filter=Qry023CustomersWithoutMembershipsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry023CustomersWithoutMembershipsTest.php
```

#### QRY-024 — Customers with active memberships

```bash
# Run by test class
php artisan test --filter=Qry024CustomersWithActiveMembershipsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry024CustomersWithActiveMembershipsTest.php
```

#### QRY-025 — Customers without active memberships

```bash
# Run by test class
php artisan test --filter=Qry025CustomersWithoutActiveMembershipsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry025CustomersWithoutActiveMembershipsTest.php
```

#### QRY-026 — Customers with multiple memberships

```bash
# Run by test class
php artisan test --filter=Qry026CustomersWithMultipleMembershipsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry026CustomersWithMultipleMembershipsTest.php
```

#### QRY-027 — Organizations with customer count

```bash
# Run by test class
php artisan test --filter=Qry027OrganizationsWithCustomerCountTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry027OrganizationsWithCustomerCountTest.php
```

#### QRY-028 — Customers with membership count

```bash
# Run by test class
php artisan test --filter=Qry028CustomersWithMembershipCountTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry028CustomersWithMembershipCountTest.php
```

#### QRY-029 — Plans with active membership count

```bash
# Run by test class
php artisan test --filter=Qry029PlansWithActiveMembershipCountTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry029PlansWithActiveMembershipCountTest.php
```

#### QRY-030 — High-risk customers

```bash
# Run by test class
php artisan test --filter=Qry030HighRiskCustomersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry030HighRiskCustomersTest.php
```

#### QRY-031 — Currently active memberships

```bash
# Run by test class
php artisan test --filter=Qry031CurrentActiveMembershipsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry031CurrentActiveMembershipsTest.php
```

#### QRY-032 — Memberships expired by end date

```bash
# Run by test class
php artisan test --filter=Qry032ExpiredMembershipsByEndDateTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry032ExpiredMembershipsByEndDateTest.php
```

#### QRY-033 — Upcoming memberships

```bash
# Run by test class
php artisan test --filter=Qry033UpcomingMembershipsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry033UpcomingMembershipsTest.php
```

#### QRY-034 — Active auto-renewing memberships

```bash
# Run by test class
php artisan test --filter=Qry034AutoRenewingMembershipsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry034AutoRenewingMembershipsTest.php
```

#### QRY-035 — Cancelled memberships with a reason

```bash
# Run by test class
php artisan test --filter=Qry035CancelledMembershipsWithReasonTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry035CancelledMembershipsWithReasonTest.php
```

#### QRY-036 — Membership status timeline

```bash
# Run by test class
php artisan test --filter=Qry036MembershipStatusTimelineTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry036MembershipStatusTimelineTest.php
```

#### QRY-037 — Memberships with latest status history

```bash
# Run by test class
php artisan test --filter=Qry037MembershipsWithLatestStatusTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry037MembershipsWithLatestStatusTest.php
```

#### QRY-038 — Membership status mismatches

```bash
# Run by test class
php artisan test --filter=Qry038MembershipStatusMismatchesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry038MembershipStatusMismatchesTest.php
```

#### QRY-039 — Stale customer profiles

```bash
# Run by test class
php artisan test --filter=Qry039StaleCustomerProfilesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry039StaleCustomerProfilesTest.php
```

#### QRY-040 — Overlapping memberships

```bash
# Run by test class
php artisan test --filter=Qry040OverlappingMembershipsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry040OverlappingMembershipsTest.php
```

#### QRY-041 — Membership count per status

```bash
# Run by test class
php artisan test --filter=Qry041MembershipCountByStatusTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry041MembershipCountByStatusTest.php
```

#### QRY-042 — Average plan price per organization

```bash
# Run by test class
php artisan test --filter=Qry042AveragePlanPriceByOrganizationTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry042AveragePlanPriceByOrganizationTest.php
```

#### QRY-043 — Membership revenue per organization

```bash
# Run by test class
php artisan test --filter=Qry043MembershipRevenueByOrganizationTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry043MembershipRevenueByOrganizationTest.php
```

#### QRY-044 — Most expensive plan per organization

```bash
# Run by test class
php artisan test --filter=Qry044MostExpensivePlanPerOrganizationTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry044MostExpensivePlanPerOrganizationTest.php
```

#### QRY-045 — Organization with most customers

```bash
# Run by test class
php artisan test --filter=Qry045OrganizationWithMostCustomersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry045OrganizationWithMostCustomersTest.php
```

#### QRY-046 — Customer with most memberships

```bash
# Run by test class
php artisan test --filter=Qry046CustomerWithMostMembershipsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry046CustomerWithMostMembershipsTest.php
```

#### QRY-047 — Duplicate external identifiers

```bash
# Run by test class
php artisan test --filter=Qry047DuplicateExternalIdentifiersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry047DuplicateExternalIdentifiersTest.php
```

#### QRY-048 — Cross-organization verified identifiers

```bash
# Run by test class
php artisan test --filter=Qry048CrossOrganizationVerifiedIdentifiersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry048CrossOrganizationVerifiedIdentifiersTest.php
```

#### QRY-049 — Customer membership report

```bash
# Run by test class
php artisan test --filter=Qry049CustomerMembershipReportTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry049CustomerMembershipReportTest.php
```

#### QRY-050 — Organization health report

```bash
# Run by test class
php artisan test --filter=Qry050OrganizationHealthReportTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry050OrganizationHealthReportTest.php
```

### Phase Two — QRY-051 through QRY-075

```bash
# Run all tests in this phase
php artisan test tests/Feature/QueryTickets/PhaseTwo
```

#### QRY-051 — Active venues

```bash
# Run by test class
php artisan test --filter=Qry051ActiveVenuesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry051ActiveVenuesTest.php
```

#### QRY-052 — Facilities for one venue

```bash
# Run by test class
php artisan test --filter=Qry052FacilitiesForGreenValleyVenueTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry052FacilitiesForGreenValleyVenueTest.php
```

#### QRY-053 — Bookable active resources

```bash
# Run by test class
php artisan test --filter=Qry053BookableActiveResourcesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry053BookableActiveResourcesTest.php
```

#### QRY-054 — Resources in maintenance

```bash
# Run by test class
php artisan test --filter=Qry054MaintenanceResourcesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry054MaintenanceResourcesTest.php
```

#### QRY-055 — Upcoming reservations

```bash
# Run by test class
php artisan test --filter=Qry055UpcomingReservationsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry055UpcomingReservationsTest.php
```

#### QRY-056 — Reservations with customer and venue

```bash
# Run by test class
php artisan test --filter=Qry056ReservationsWithCustomerAndVenueTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry056ReservationsWithCustomerAndVenueTest.php
```

#### QRY-057 — Reservations without participants

```bash
# Run by test class
php artisan test --filter=Qry057ReservationsWithoutParticipantsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry057ReservationsWithoutParticipantsTest.php
```

#### QRY-058 — Reservations with participant count

```bash
# Run by test class
php artisan test --filter=Qry058ReservationsWithParticipantCountTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry058ReservationsWithParticipantCountTest.php
```

#### QRY-059 — Resources never booked

```bash
# Run by test class
php artisan test --filter=Qry059ResourcesNeverBookedTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry059ResourcesNeverBookedTest.php
```

#### QRY-060 — Reservations with multiple resources

```bash
# Run by test class
php artisan test --filter=Qry060ReservationsWithMultipleResourcesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry060ReservationsWithMultipleResourcesTest.php
```

#### QRY-061 — Overlapping reservations on the same resource

```bash
# Run by test class
php artisan test --filter=Qry061OverlappingResourceReservationsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry061OverlappingResourceReservationsTest.php
```

#### QRY-062 — Reservations during resource availability blocks

```bash
# Run by test class
php artisan test --filter=Qry062ReservationsDuringAvailabilityBlocksTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry062ReservationsDuringAvailabilityBlocksTest.php
```

#### QRY-063 — Reservations exceeding resource capacity

```bash
# Run by test class
php artisan test --filter=Qry063ReservationsExceedingResourceCapacityTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry063ReservationsExceedingResourceCapacityTest.php
```

#### QRY-064 — Reservation participant count mismatches

```bash
# Run by test class
php artisan test --filter=Qry064ReservationParticipantCountMismatchesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry064ReservationParticipantCountMismatchesTest.php
```

#### QRY-065 — Reservations without a creating user

```bash
# Run by test class
php artisan test --filter=Qry065ReservationsWithoutCreatorTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry065ReservationsWithoutCreatorTest.php
```

#### QRY-066 — Customers with upcoming reservations

```bash
# Run by test class
php artisan test --filter=Qry066CustomersWithUpcomingReservationsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry066CustomersWithUpcomingReservationsTest.php
```

#### QRY-067 — Venues with reservation count

```bash
# Run by test class
php artisan test --filter=Qry067VenuesWithReservationCountTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry067VenuesWithReservationCountTest.php
```

#### QRY-068 — Resources with upcoming reservation count

```bash
# Run by test class
php artisan test --filter=Qry068ResourcesWithUpcomingReservationCountTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry068ResourcesWithUpcomingReservationCountTest.php
```

#### QRY-069 — Reservation items with nested resource location

```bash
# Run by test class
php artisan test --filter=Qry069ReservationItemsWithResourceLocationTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry069ReservationItemsWithResourceLocationTest.php
```

#### QRY-070 — Reservations with checked-in participants

```bash
# Run by test class
php artisan test --filter=Qry070ReservationsWithCheckedInParticipantsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry070ReservationsWithCheckedInParticipantsTest.php
```

#### QRY-071 — Reservation status mismatches

```bash
# Run by test class
php artisan test --filter=Qry071ReservationStatusMismatchesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry071ReservationStatusMismatchesTest.php
```

#### QRY-072 — Reservation status timeline

```bash
# Run by test class
php artisan test --filter=Qry072ReservationStatusTimelineTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry072ReservationStatusTimelineTest.php
```

#### QRY-073 — Venue reservation revenue

```bash
# Run by test class
php artisan test --filter=Qry073VenueReservationRevenueTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry073VenueReservationRevenueTest.php
```

#### QRY-074 — Resources with non-cancelled booking count

```bash
# Run by test class
php artisan test --filter=Qry074ResourcesWithNonCancelledBookingCountTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry074ResourcesWithNonCancelledBookingCountTest.php
```

#### QRY-075 — Organization reservation health report

```bash
# Run by test class
php artisan test --filter=Qry075OrganizationReservationHealthReportTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry075OrganizationReservationHealthReportTest.php
```

### Phase Three — QRY-076 through QRY-095

```bash
# Run all tests in this phase
php artisan test tests/Feature/QueryTickets/PhaseThree
```

#### QRY-076 — Currently active price rules

```bash
# Run by test class
php artisan test --filter=Qry076CurrentlyActivePriceRulesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry076CurrentlyActivePriceRulesTest.php
```

#### QRY-077 — Reservation charge totals

```bash
# Run by test class
php artisan test --filter=Qry077ReservationChargeTotalsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry077ReservationChargeTotalsTest.php
```

#### QRY-078 — Reservations without payments

```bash
# Run by test class
php artisan test --filter=Qry078ReservationsWithoutPaymentsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry078ReservationsWithoutPaymentsTest.php
```

#### QRY-079 — Underpaid reservations

```bash
# Run by test class
php artisan test --filter=Qry079UnderpaidReservationsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry079UnderpaidReservationsTest.php
```

#### QRY-080 — Failed payments

```bash
# Run by test class
php artisan test --filter=Qry080FailedPaymentsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry080FailedPaymentsTest.php
```

#### QRY-081 — Partially refunded payments

```bash
# Run by test class
php artisan test --filter=Qry081PartiallyRefundedPaymentsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry081PartiallyRefundedPaymentsTest.php
```

#### QRY-082 — Fully refunded payments

```bash
# Run by test class
php artisan test --filter=Qry082FullyRefundedPaymentsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry082FullyRefundedPaymentsTest.php
```

#### QRY-083 — Net paid amount per reservation

```bash
# Run by test class
php artisan test --filter=Qry083NetPaidAmountPerReservationTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry083NetPaidAmountPerReservationTest.php
```

#### QRY-084 — Customers with credit accounts

```bash
# Run by test class
php artisan test --filter=Qry084CustomersWithCreditAccountsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry084CustomersWithCreditAccountsTest.php
```

#### QRY-085 — Expired credit accounts with balance

```bash
# Run by test class
php artisan test --filter=Qry085ExpiredCreditAccountsWithBalanceTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry085ExpiredCreditAccountsWithBalanceTest.php
```

#### QRY-086 — Credit balance mismatches

```bash
# Run by test class
php artisan test --filter=Qry086CreditBalanceMismatchesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry086CreditBalanceMismatchesTest.php
```

#### QRY-087 — Credit transaction running balances

```bash
# Run by test class
php artisan test --filter=Qry087CreditTransactionRunningBalancesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry087CreditTransactionRunningBalancesTest.php
```

#### QRY-088 — Payment transaction timeline

```bash
# Run by test class
php artisan test --filter=Qry088PaymentTransactionTimelineTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry088PaymentTransactionTimelineTest.php
```

#### QRY-089 — Payments with multiple transactions

```bash
# Run by test class
php artisan test --filter=Qry089PaymentsWithMultipleTransactionsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry089PaymentsWithMultipleTransactionsTest.php
```

#### QRY-090 — Refund reconciliation

```bash
# Run by test class
php artisan test --filter=Qry090RefundReconciliationTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry090RefundReconciliationTest.php
```

#### QRY-091 — Customer payment summary

```bash
# Run by test class
php artisan test --filter=Qry091CustomerPaymentSummaryTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry091CustomerPaymentSummaryTest.php
```

#### QRY-092 — Organization net revenue

```bash
# Run by test class
php artisan test --filter=Qry092OrganizationNetRevenueTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry092OrganizationNetRevenueTest.php
```

#### QRY-093 — Venue net revenue

```bash
# Run by test class
php artisan test --filter=Qry093VenueNetRevenueTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry093VenueNetRevenueTest.php
```

#### QRY-094 — Payment method breakdown

```bash
# Run by test class
php artisan test --filter=Qry094PaymentMethodBreakdownTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry094PaymentMethodBreakdownTest.php
```

#### QRY-095 — Financial health report

```bash
# Run by test class
php artisan test --filter=Qry095FinancialHealthReportTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry095FinancialHealthReportTest.php
```

### Phase Four — QRY-096 through QRY-120

```bash
# Run all tests in this phase
php artisan test tests/Feature/QueryTickets/PhaseFour
```

#### QRY-096 — Active products with category

```bash
# Run by test class
php artisan test --filter=Qry096ActiveProductsWithCategoryTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry096ActiveProductsWithCategoryTest.php
```

#### QRY-097 — Products without variants

```bash
# Run by test class
php artisan test --filter=Qry097ProductsWithoutVariantsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry097ProductsWithoutVariantsTest.php
```

#### QRY-098 — Stock-tracked variants without inventory

```bash
# Run by test class
php artisan test --filter=Qry098StockTrackedVariantsWithoutInventoryTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry098StockTrackedVariantsWithoutInventoryTest.php
```

#### QRY-099 — Available stock per location

```bash
# Run by test class
php artisan test --filter=Qry099AvailableStockPerLocationTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry099AvailableStockPerLocationTest.php
```

#### QRY-100 — Low-stock inventory levels

```bash
# Run by test class
php artisan test --filter=Qry100LowStockInventoryLevelsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry100LowStockInventoryLevelsTest.php
```

#### QRY-101 — Out-of-stock variants

```bash
# Run by test class
php artisan test --filter=Qry101OutOfStockVariantsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry101OutOfStockVariantsTest.php
```

#### QRY-102 — Sales orders without items

```bash
# Run by test class
php artisan test --filter=Qry102SalesOrdersWithoutItemsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry102SalesOrdersWithoutItemsTest.php
```

#### QRY-103 — Sales orders with item count

```bash
# Run by test class
php artisan test --filter=Qry103SalesOrdersWithItemCountTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry103SalesOrdersWithItemCountTest.php
```

#### QRY-104 — Customers with multiple sales orders

```bash
# Run by test class
php artisan test --filter=Qry104CustomersWithMultipleSalesOrdersTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry104CustomersWithMultipleSalesOrdersTest.php
```

#### QRY-105 — Sales order total mismatches

```bash
# Run by test class
php artisan test --filter=Qry105SalesOrderTotalMismatchesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry105SalesOrderTotalMismatchesTest.php
```

#### QRY-106 — Inventory movement timeline

```bash
# Run by test class
php artisan test --filter=Qry106InventoryMovementTimelineTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry106InventoryMovementTimelineTest.php
```

#### QRY-107 — Inventory running balances

```bash
# Run by test class
php artisan test --filter=Qry107InventoryRunningBalancesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry107InventoryRunningBalancesTest.php
```

#### QRY-108 — Inventory level mismatches

```bash
# Run by test class
php artisan test --filter=Qry108InventoryLevelMismatchesTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry108InventoryLevelMismatchesTest.php
```

#### QRY-109 — Best-selling variants

```bash
# Run by test class
php artisan test --filter=Qry109BestSellingVariantsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry109BestSellingVariantsTest.php
```

#### QRY-110 — Product sales summary

```bash
# Run by test class
php artisan test --filter=Qry110ProductSalesSummaryTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry110ProductSalesSummaryTest.php
```

#### QRY-111 — Product revenue by organization

```bash
# Run by test class
php artisan test --filter=Qry111ProductRevenueByOrganizationTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry111ProductRevenueByOrganizationTest.php
```

#### QRY-112 — Sales revenue by venue

```bash
# Run by test class
php artisan test --filter=Qry112SalesRevenueByVenueTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry112SalesRevenueByVenueTest.php
```

#### QRY-113 — Customer purchase summary

```bash
# Run by test class
php artisan test --filter=Qry113CustomerPurchaseSummaryTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry113CustomerPurchaseSummaryTest.php
```

#### QRY-114 — Stock location inventory report

```bash
# Run by test class
php artisan test --filter=Qry114StockLocationInventoryReportTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry114StockLocationInventoryReportTest.php
```

#### QRY-115 — Inventory and sales health report

```bash
# Run by test class
php artisan test --filter=Qry115InventoryAndSalesHealthReportTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry115InventoryAndSalesHealthReportTest.php
```

#### QRY-116 — Categories with product count

```bash
# Run by test class
php artisan test --filter=Qry116CategoriesWithProductCountTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry116CategoriesWithProductCountTest.php
```

#### QRY-117 — Variants stocked at multiple locations

```bash
# Run by test class
php artisan test --filter=Qry117VariantsStockedAtMultipleLocationsTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry117VariantsStockedAtMultipleLocationsTest.php
```

#### QRY-118 — Inventory movement summary by type

```bash
# Run by test class
php artisan test --filter=Qry118InventoryMovementSummaryByTypeTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry118InventoryMovementSummaryByTypeTest.php
```

#### QRY-119 — Sales order status summary

```bash
# Run by test class
php artisan test --filter=Qry119SalesOrderStatusSummaryTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry119SalesOrderStatusSummaryTest.php
```

#### QRY-120 — Organization commerce report

```bash
# Run by test class
php artisan test --filter=Qry120OrganizationCommerceReportTest

# Run the exact test file
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry120OrganizationCommerceReportTest.php
```

## Important directories

```text
app/Models/
    Eloquent models and relationships

app/QueryTickets/
    The exercises you solve

database/factories/
    Factories for valid test data

database/migrations/
    Database schema and PostgreSQL constraints

database/seeders/
    The fixed datasets for each phase

docs/database/
    Database blueprints and phase descriptions

tests/Feature/Data/
    Tests for factories, relationships, and seeded scenarios

tests/Feature/QueryTickets/
    Exact tests for all Query Tickets
```

## Work in a personal exercise environment

The public repository contains the unsolved exercises. For your own progress, use a personal branch or a separate clone:

```bash
git clone https://github.com/PascalVuong/QueryAutism.git query-autism-exercises
cd query-autism-exercises
git checkout -b learning/query-progress
```

This keeps the original exercise repository clean and allows you to commit your own solutions separately.

## Where should you start?

Start with:

```text
QRY-001 — Active users
```

QRY-001 also includes an official example solution that demonstrates how a Query Ticket, its result, and its test work together.

Continue through the tickets one by one. You will start with simple filters and eventually work with joins, subqueries, reconciliation reports, and PostgreSQL window functions.
