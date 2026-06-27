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
