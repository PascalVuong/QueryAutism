# QueryAutism

[Read this README in English](README.md)

QueryAutism is een Laravel- en PostgreSQL-leerproject voor het stap voor stap oefenen van databasequeries.

De repository bevat een realistische, multi-tenant SaaS-dataset met organisaties, klanten, memberships, reserveringen, betalingen, voorraad en verkooporders. Iedere oefening heeft een eigen Query Ticket en een automatische test die controleert of het resultaat exact klopt.

## Wat leer je?

De oefeningen bouwen geleidelijk op van eenvoudige Eloquent-queries naar uitgebreide rapportages.

Onderwerpen die aan bod komen:

* filteren, sorteren en kolommen selecteren;
* Eloquent-relaties en eager loading;
* `whereHas`, `doesntHave`, `withCount` en `withSum`;
* joins en subqueries;
* `GROUP BY`, `HAVING`, `COUNT`, `SUM` en `AVG`;
* berekende kolommen en reconciliation-queries;
* PostgreSQL window functions zoals `SUM OVER` en `PARTITION BY`;
* rapportages over meerdere organisaties en tabellen.

Je mag een ticket oplossen met Eloquent, de Query Builder of raw SQL, zolang het resultaat voldoet aan het contract van de oefening.

## Projectinhoud

De leeromgeving bestaat uit vier fases en 120 Query Tickets.

| Fase        |             Tickets | Onderwerp                    |
| ----------- | ------------------: | ---------------------------- |
| Phase One   | QRY-001 t/m QRY-050 | Identity & Memberships       |
| Phase Two   | QRY-051 t/m QRY-075 | Reservations & Resources     |
| Phase Three | QRY-076 t/m QRY-095 | Pricing & Payments           |
| Phase Four  | QRY-096 t/m QRY-120 | Products, Inventory & Orders |

De seeders maken steeds dezelfde dataset aan. Daardoor geven de tests voorspelbare resultaten en kun je een query opnieuw uitvoeren zonder dat de verwachte uitkomst verandert.

#### Waarom is een aparte testdatabase nodig?

De tests gebruiken Laravel's `RefreshDatabase`-functionaliteit. Hierdoor kan Laravel tijdens het testen:

* tabellen verwijderen of opnieuw opbouwen;
* migrations opnieuw uitvoeren;
* de vaste scenarioseeders uitvoeren;
* iedere test met een schone database laten beginnen.

Wanneer de tests dezelfde database zouden gebruiken als de normale applicatie, kunnen je lokale gegevens worden verwijderd.

Met gescheiden databases blijft je normale database veilig:

```text
.env
└── DB_DATABASE=query_autism

.env.testing
└── DB_DATABASE=query_autism_testing
```

Wanneer je `php artisan test` uitvoert, gebruikt Laravel automatisch de instellingen uit `.env.testing`.

> **Let op:** gebruik voor `DB_DATABASE` in `.env.testing` nooit dezelfde database als in `.env`.

## Technische vereisten

* PHP 8.4 of hoger
* Composer
* PostgreSQL 17
* Laravel 13
* Git

## Installatie

### 1. Repository clonen

```bash
git clone https://github.com/PascalVuong/QueryAutism.git
cd QueryAutism
```

### 2. PHP-dependencies installeren

```bash
composer install
```

### 3. Environmentbestand maken

```bash
cp .env.example .env
php artisan key:generate
```

### 4. PostgreSQL-databases maken

Maak één database voor lokaal gebruik en één aparte database voor de tests:

```bash
createdb query_autism
createdb query_autism_testing
```

Pas daarna de database-instellingen in `.env` aan:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=query_autism
DB_USERNAME=postgres
DB_PASSWORD=
```

Gebruik je een andere PostgreSQL-gebruiker of een wachtwoord, vul dan je eigen gegevens in.

### 5. Testomgeving instellen

Maak een apart test-environmentbestand:

```bash
cp .env .env.testing
```

Pas in `.env.testing` minimaal deze waarden aan:

```dotenv
APP_ENV=testing
DB_DATABASE=query_autism_testing
```

### 6. Database opbouwen en vullen

```bash
php artisan migrate:fresh --seed
```

Hiermee worden alle tabellen opnieuw gemaakt en worden de vier vaste scenarioseeders uitgevoerd.

### 7. Applicatie starten

```bash
php artisan serve
```

Open daarna:

```text
http://127.0.0.1:8000/queries
```

## Hoe werkt een Query Ticket?

Iedere oefening staat in een aparte class onder:

```text
app/QueryTickets/
```

Voorbeeld:

```text
app/QueryTickets/PhaseOne/Qry001ActiveUsers.php
```

Een Query Ticket bevat onder andere:

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

Je opdracht is om alleen de inhoud van `run()` te vervangen door een query die het gevraagde resultaat teruggeeft.

## Aanbevolen oefenworkflow

### 1. Kies een ticket

Open het dashboard en begin bij het eerste onopgeloste ticket:

```text
http://127.0.0.1:8000/queries
```

Werk bij voorkeur op volgorde. Latere tickets bouwen voort op technieken uit eerdere oefeningen.

### 2. Lees het contract

Controleer in de ticketclass:

* de beschrijving;
* de genoemde concepten;
* de verwachte kolommen;
* de gewenste sortering.

Open daarna ook de bijbehorende test onder:

```text
tests/Feature/QueryTickets/
```

De test laat exact zien welke records, kolommen, relaties en volgorde worden verwacht.

### 3. Schrijf de query

Vervang in `run()` de tijdelijke exception:

```php
throw new LogicException('QRY-001 has not been solved yet.');
```

door je query:

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

Dit is alleen een vormvoorbeeld. Gebruik bij ieder ticket de tabellen, relaties, filters en kolommen die bij die oefening horen.

### 4. Voer alleen de test van het ticket uit

Met het volledige testbestand:

```bash
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry001ActiveUsersTest.php
```

Of met een filter:

```bash
php artisan test --filter=Qry001ActiveUsersTest
```

### 5. Interpreteer de uitkomst

Een ticket kan drie toestanden hebben:

| Status     | Betekenis                                                        |
| ---------- | ---------------------------------------------------------------- |
| Incomplete | `run()` bevat nog de tijdelijke `LogicException`                 |
| Failed     | Er staat een query, maar het resultaat is nog niet exact correct |
| Passed     | De query retourneert exact het verwachte resultaat               |

Een verkeerde query wordt dus niet als incomplete gemarkeerd. De test faalt en toont welk onderdeel niet klopt.

### 6. Controleer de volledige suite

Wanneer je ticket slaagt:

```bash
php artisan test
```

Zo controleer je dat jouw oplossing geen andere oefeningen of projectonderdelen heeft beschadigd.

### 7. Commit je voortgang

```bash
git add app/QueryTickets
git commit -m "Solve QRY-001 active users"
```

## Belangrijke oefenregels

### Verander de tests niet om een query te laten slagen

De tests zijn het contract van de oefening. Pas je query aan, niet de verwachte uitkomst.

### Verander de scenarioseeders niet tijdens het oplossen

De vaste dataset zorgt ervoor dat iedere oefening reproduceerbaar blijft.

Database opnieuw opbouwen:

```bash
php artisan migrate:fresh --seed
```

### Retourneer alleen de gevraagde kolommen

Veel tests controleren de kolommen exact:

```php
->get([
    'id',
    'name',
    'email',
]);
```

`select('*')` of een model met extra attributen kan daarom terecht een failure veroorzaken.

### Let op foreign keys bij eager loading

Wanneer je geselecteerde kolommen beperkt, moet de foreign key die Eloquent nodig heeft aanwezig blijven.

Bijvoorbeeld:

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

Zonder `product_category_id` kan Eloquent de categorie niet aan het product koppelen.

### Volg de gevraagde volgorde

De tests controleren vaak niet alleen de records, maar ook hun volgorde. Voeg daarom de gevraagde `orderBy()`-clausules toe.

## Handige commando’s

Database opnieuw maken:

```bash
php artisan migrate:fresh --seed
```

Alle datatests uitvoeren:

```bash
php artisan test tests/Feature/Data
```

Alle Query Tickets van een fase uitvoeren:

```bash
php artisan test tests/Feature/QueryTickets/PhaseOne
php artisan test tests/Feature/QueryTickets/PhaseTwo
php artisan test tests/Feature/QueryTickets/PhaseThree
php artisan test tests/Feature/QueryTickets/PhaseFour
```

Volledige testsuite uitvoeren:

```bash
php artisan test
```

Beschikbare routes bekijken:

```bash
php artisan route:list
```

## Testcommando's voor QRY-001 t/m QRY-120

Bij ieder ticket staan beide beschikbare commando's: een kort `--filter`-commando en het commando voor het exacte testbestand. De ticketnamen zijn bewust Engels gebleven, zodat ze exact overeenkomen met de namen op het QueryAutism-dashboard.

Laravel gebruikt voor deze commando's `.env.testing`. De tests draaien daardoor op de aparte testdatabase en niet op je normale lokale database.

### Meerdere tests uitvoeren

```bash
# Alle Query Ticket-tests uitvoeren
php artisan test tests/Feature/QueryTickets

# Alle data-, factory-, relatie- en seedertests uitvoeren
php artisan test tests/Feature/Data

# De volledige test-suite van het project uitvoeren
php artisan test
```

### Phase One — QRY-001 t/m QRY-050

```bash
# Alle tests van deze fase uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne
```

#### QRY-001 — Active users ordered by latest login

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry001ActiveUsersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry001ActiveUsersTest.php
```

#### QRY-002 — Unverified users

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry002UnverifiedUsersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry002UnverifiedUsersTest.php
```

#### QRY-003 — Users who never logged in

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry003UsersWhoNeverLoggedInTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry003UsersWhoNeverLoggedInTest.php
```

#### QRY-004 — Suspended users

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry004SuspendedUsersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry004SuspendedUsersTest.php
```

#### QRY-005 — Recent logins

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry005RecentLoginsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry005RecentLoginsTest.php
```

#### QRY-006 — Users with Dutch locale

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry006DutchLocaleUsersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry006DutchLocaleUsersTest.php
```

#### QRY-007 — Active or pending users

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry007ActiveOrPendingUsersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry007ActiveOrPendingUsersTest.php
```

#### QRY-008 — Guest customers

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry008GuestCustomersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry008GuestCustomersTest.php
```

#### QRY-009 — Customers with marketing consent

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry009CustomersWithMarketingConsentTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry009CustomersWithMarketingConsentTest.php
```

#### QRY-010 — Active membership plans by price

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry010ActiveMembershipPlansByPriceTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry010ActiveMembershipPlansByPriceTest.php
```

#### QRY-011 — Users with profiles

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry011UsersWithProfilesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry011UsersWithProfilesTest.php
```

#### QRY-012 — Users without profiles

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry012UsersWithoutProfilesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry012UsersWithoutProfilesTest.php
```

#### QRY-013 — Customers without user accounts

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry013CustomersWithoutAccountsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry013CustomersWithoutAccountsTest.php
```

#### QRY-014 — Customers with user accounts

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry014CustomersWithUserAccountsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry014CustomersWithUserAccountsTest.php
```

#### QRY-015 — Customers with profiles

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry015CustomersWithProfilesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry015CustomersWithProfilesTest.php
```

#### QRY-016 — Child organizations with parent

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry016ChildOrganizationsWithParentTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry016ChildOrganizationsWithParentTest.php
```

#### QRY-017 — Parent organizations with children

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry017ParentOrganizationsWithChildrenTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry017ParentOrganizationsWithChildrenTest.php
```

#### QRY-018 — Users with organizations

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry018UsersWithOrganizationsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry018UsersWithOrganizationsTest.php
```

#### QRY-019 — Users in multiple organizations

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry019UsersInMultipleOrganizationsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry019UsersInMultipleOrganizationsTest.php
```

#### QRY-020 — Memberships with customer and plan

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry020MembershipsWithCustomerAndPlanTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry020MembershipsWithCustomerAndPlanTest.php
```

#### QRY-021 — Organizations with customers

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry021OrganizationsWithCustomersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry021OrganizationsWithCustomersTest.php
```

#### QRY-022 — Organizations without customers

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry022OrganizationsWithoutCustomersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry022OrganizationsWithoutCustomersTest.php
```

#### QRY-023 — Customers without memberships

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry023CustomersWithoutMembershipsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry023CustomersWithoutMembershipsTest.php
```

#### QRY-024 — Customers with active memberships

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry024CustomersWithActiveMembershipsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry024CustomersWithActiveMembershipsTest.php
```

#### QRY-025 — Customers without active memberships

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry025CustomersWithoutActiveMembershipsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry025CustomersWithoutActiveMembershipsTest.php
```

#### QRY-026 — Customers with multiple memberships

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry026CustomersWithMultipleMembershipsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry026CustomersWithMultipleMembershipsTest.php
```

#### QRY-027 — Organizations with customer count

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry027OrganizationsWithCustomerCountTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry027OrganizationsWithCustomerCountTest.php
```

#### QRY-028 — Customers with membership count

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry028CustomersWithMembershipCountTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry028CustomersWithMembershipCountTest.php
```

#### QRY-029 — Plans with active membership count

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry029PlansWithActiveMembershipCountTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry029PlansWithActiveMembershipCountTest.php
```

#### QRY-030 — High-risk customers

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry030HighRiskCustomersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry030HighRiskCustomersTest.php
```

#### QRY-031 — Currently active memberships

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry031CurrentActiveMembershipsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry031CurrentActiveMembershipsTest.php
```

#### QRY-032 — Memberships expired by end date

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry032ExpiredMembershipsByEndDateTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry032ExpiredMembershipsByEndDateTest.php
```

#### QRY-033 — Upcoming memberships

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry033UpcomingMembershipsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry033UpcomingMembershipsTest.php
```

#### QRY-034 — Active auto-renewing memberships

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry034AutoRenewingMembershipsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry034AutoRenewingMembershipsTest.php
```

#### QRY-035 — Cancelled memberships with a reason

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry035CancelledMembershipsWithReasonTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry035CancelledMembershipsWithReasonTest.php
```

#### QRY-036 — Membership status timeline

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry036MembershipStatusTimelineTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry036MembershipStatusTimelineTest.php
```

#### QRY-037 — Memberships with latest status history

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry037MembershipsWithLatestStatusTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry037MembershipsWithLatestStatusTest.php
```

#### QRY-038 — Membership status mismatches

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry038MembershipStatusMismatchesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry038MembershipStatusMismatchesTest.php
```

#### QRY-039 — Stale customer profiles

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry039StaleCustomerProfilesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry039StaleCustomerProfilesTest.php
```

#### QRY-040 — Overlapping memberships

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry040OverlappingMembershipsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry040OverlappingMembershipsTest.php
```

#### QRY-041 — Membership count per status

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry041MembershipCountByStatusTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry041MembershipCountByStatusTest.php
```

#### QRY-042 — Average plan price per organization

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry042AveragePlanPriceByOrganizationTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry042AveragePlanPriceByOrganizationTest.php
```

#### QRY-043 — Membership revenue per organization

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry043MembershipRevenueByOrganizationTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry043MembershipRevenueByOrganizationTest.php
```

#### QRY-044 — Most expensive plan per organization

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry044MostExpensivePlanPerOrganizationTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry044MostExpensivePlanPerOrganizationTest.php
```

#### QRY-045 — Organization with most customers

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry045OrganizationWithMostCustomersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry045OrganizationWithMostCustomersTest.php
```

#### QRY-046 — Customer with most memberships

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry046CustomerWithMostMembershipsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry046CustomerWithMostMembershipsTest.php
```

#### QRY-047 — Duplicate external identifiers

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry047DuplicateExternalIdentifiersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry047DuplicateExternalIdentifiersTest.php
```

#### QRY-048 — Cross-organization verified identifiers

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry048CrossOrganizationVerifiedIdentifiersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry048CrossOrganizationVerifiedIdentifiersTest.php
```

#### QRY-049 — Customer membership report

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry049CustomerMembershipReportTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry049CustomerMembershipReportTest.php
```

#### QRY-050 — Organization health report

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry050OrganizationHealthReportTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseOne/Qry050OrganizationHealthReportTest.php
```

### Phase Two — QRY-051 t/m QRY-075

```bash
# Alle tests van deze fase uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo
```

#### QRY-051 — Active venues

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry051ActiveVenuesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry051ActiveVenuesTest.php
```

#### QRY-052 — Facilities for one venue

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry052FacilitiesForGreenValleyVenueTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry052FacilitiesForGreenValleyVenueTest.php
```

#### QRY-053 — Bookable active resources

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry053BookableActiveResourcesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry053BookableActiveResourcesTest.php
```

#### QRY-054 — Resources in maintenance

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry054MaintenanceResourcesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry054MaintenanceResourcesTest.php
```

#### QRY-055 — Upcoming reservations

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry055UpcomingReservationsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry055UpcomingReservationsTest.php
```

#### QRY-056 — Reservations with customer and venue

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry056ReservationsWithCustomerAndVenueTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry056ReservationsWithCustomerAndVenueTest.php
```

#### QRY-057 — Reservations without participants

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry057ReservationsWithoutParticipantsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry057ReservationsWithoutParticipantsTest.php
```

#### QRY-058 — Reservations with participant count

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry058ReservationsWithParticipantCountTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry058ReservationsWithParticipantCountTest.php
```

#### QRY-059 — Resources never booked

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry059ResourcesNeverBookedTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry059ResourcesNeverBookedTest.php
```

#### QRY-060 — Reservations with multiple resources

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry060ReservationsWithMultipleResourcesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry060ReservationsWithMultipleResourcesTest.php
```

#### QRY-061 — Overlapping reservations on the same resource

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry061OverlappingResourceReservationsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry061OverlappingResourceReservationsTest.php
```

#### QRY-062 — Reservations during resource availability blocks

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry062ReservationsDuringAvailabilityBlocksTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry062ReservationsDuringAvailabilityBlocksTest.php
```

#### QRY-063 — Reservations exceeding resource capacity

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry063ReservationsExceedingResourceCapacityTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry063ReservationsExceedingResourceCapacityTest.php
```

#### QRY-064 — Reservation participant count mismatches

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry064ReservationParticipantCountMismatchesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry064ReservationParticipantCountMismatchesTest.php
```

#### QRY-065 — Reservations without a creating user

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry065ReservationsWithoutCreatorTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry065ReservationsWithoutCreatorTest.php
```

#### QRY-066 — Customers with upcoming reservations

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry066CustomersWithUpcomingReservationsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry066CustomersWithUpcomingReservationsTest.php
```

#### QRY-067 — Venues with reservation count

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry067VenuesWithReservationCountTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry067VenuesWithReservationCountTest.php
```

#### QRY-068 — Resources with upcoming reservation count

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry068ResourcesWithUpcomingReservationCountTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry068ResourcesWithUpcomingReservationCountTest.php
```

#### QRY-069 — Reservation items with nested resource location

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry069ReservationItemsWithResourceLocationTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry069ReservationItemsWithResourceLocationTest.php
```

#### QRY-070 — Reservations with checked-in participants

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry070ReservationsWithCheckedInParticipantsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry070ReservationsWithCheckedInParticipantsTest.php
```

#### QRY-071 — Reservation status mismatches

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry071ReservationStatusMismatchesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry071ReservationStatusMismatchesTest.php
```

#### QRY-072 — Reservation status timeline

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry072ReservationStatusTimelineTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry072ReservationStatusTimelineTest.php
```

#### QRY-073 — Venue reservation revenue

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry073VenueReservationRevenueTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry073VenueReservationRevenueTest.php
```

#### QRY-074 — Resources with non-cancelled booking count

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry074ResourcesWithNonCancelledBookingCountTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry074ResourcesWithNonCancelledBookingCountTest.php
```

#### QRY-075 — Organization reservation health report

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry075OrganizationReservationHealthReportTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseTwo/Qry075OrganizationReservationHealthReportTest.php
```

### Phase Three — QRY-076 t/m QRY-095

```bash
# Alle tests van deze fase uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree
```

#### QRY-076 — Currently active price rules

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry076CurrentlyActivePriceRulesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry076CurrentlyActivePriceRulesTest.php
```

#### QRY-077 — Reservation charge totals

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry077ReservationChargeTotalsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry077ReservationChargeTotalsTest.php
```

#### QRY-078 — Reservations without payments

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry078ReservationsWithoutPaymentsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry078ReservationsWithoutPaymentsTest.php
```

#### QRY-079 — Underpaid reservations

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry079UnderpaidReservationsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry079UnderpaidReservationsTest.php
```

#### QRY-080 — Failed payments

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry080FailedPaymentsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry080FailedPaymentsTest.php
```

#### QRY-081 — Partially refunded payments

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry081PartiallyRefundedPaymentsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry081PartiallyRefundedPaymentsTest.php
```

#### QRY-082 — Fully refunded payments

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry082FullyRefundedPaymentsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry082FullyRefundedPaymentsTest.php
```

#### QRY-083 — Net paid amount per reservation

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry083NetPaidAmountPerReservationTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry083NetPaidAmountPerReservationTest.php
```

#### QRY-084 — Customers with credit accounts

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry084CustomersWithCreditAccountsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry084CustomersWithCreditAccountsTest.php
```

#### QRY-085 — Expired credit accounts with balance

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry085ExpiredCreditAccountsWithBalanceTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry085ExpiredCreditAccountsWithBalanceTest.php
```

#### QRY-086 — Credit balance mismatches

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry086CreditBalanceMismatchesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry086CreditBalanceMismatchesTest.php
```

#### QRY-087 — Credit transaction running balances

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry087CreditTransactionRunningBalancesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry087CreditTransactionRunningBalancesTest.php
```

#### QRY-088 — Payment transaction timeline

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry088PaymentTransactionTimelineTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry088PaymentTransactionTimelineTest.php
```

#### QRY-089 — Payments with multiple transactions

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry089PaymentsWithMultipleTransactionsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry089PaymentsWithMultipleTransactionsTest.php
```

#### QRY-090 — Refund reconciliation

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry090RefundReconciliationTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry090RefundReconciliationTest.php
```

#### QRY-091 — Customer payment summary

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry091CustomerPaymentSummaryTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry091CustomerPaymentSummaryTest.php
```

#### QRY-092 — Organization net revenue

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry092OrganizationNetRevenueTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry092OrganizationNetRevenueTest.php
```

#### QRY-093 — Venue net revenue

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry093VenueNetRevenueTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry093VenueNetRevenueTest.php
```

#### QRY-094 — Payment method breakdown

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry094PaymentMethodBreakdownTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry094PaymentMethodBreakdownTest.php
```

#### QRY-095 — Financial health report

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry095FinancialHealthReportTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseThree/Qry095FinancialHealthReportTest.php
```

### Phase Four — QRY-096 t/m QRY-120

```bash
# Alle tests van deze fase uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour
```

#### QRY-096 — Active products with category

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry096ActiveProductsWithCategoryTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry096ActiveProductsWithCategoryTest.php
```

#### QRY-097 — Products without variants

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry097ProductsWithoutVariantsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry097ProductsWithoutVariantsTest.php
```

#### QRY-098 — Stock-tracked variants without inventory

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry098StockTrackedVariantsWithoutInventoryTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry098StockTrackedVariantsWithoutInventoryTest.php
```

#### QRY-099 — Available stock per location

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry099AvailableStockPerLocationTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry099AvailableStockPerLocationTest.php
```

#### QRY-100 — Low-stock inventory levels

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry100LowStockInventoryLevelsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry100LowStockInventoryLevelsTest.php
```

#### QRY-101 — Out-of-stock variants

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry101OutOfStockVariantsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry101OutOfStockVariantsTest.php
```

#### QRY-102 — Sales orders without items

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry102SalesOrdersWithoutItemsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry102SalesOrdersWithoutItemsTest.php
```

#### QRY-103 — Sales orders with item count

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry103SalesOrdersWithItemCountTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry103SalesOrdersWithItemCountTest.php
```

#### QRY-104 — Customers with multiple sales orders

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry104CustomersWithMultipleSalesOrdersTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry104CustomersWithMultipleSalesOrdersTest.php
```

#### QRY-105 — Sales order total mismatches

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry105SalesOrderTotalMismatchesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry105SalesOrderTotalMismatchesTest.php
```

#### QRY-106 — Inventory movement timeline

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry106InventoryMovementTimelineTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry106InventoryMovementTimelineTest.php
```

#### QRY-107 — Inventory running balances

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry107InventoryRunningBalancesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry107InventoryRunningBalancesTest.php
```

#### QRY-108 — Inventory level mismatches

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry108InventoryLevelMismatchesTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry108InventoryLevelMismatchesTest.php
```

#### QRY-109 — Best-selling variants

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry109BestSellingVariantsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry109BestSellingVariantsTest.php
```

#### QRY-110 — Product sales summary

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry110ProductSalesSummaryTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry110ProductSalesSummaryTest.php
```

#### QRY-111 — Product revenue by organization

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry111ProductRevenueByOrganizationTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry111ProductRevenueByOrganizationTest.php
```

#### QRY-112 — Sales revenue by venue

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry112SalesRevenueByVenueTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry112SalesRevenueByVenueTest.php
```

#### QRY-113 — Customer purchase summary

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry113CustomerPurchaseSummaryTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry113CustomerPurchaseSummaryTest.php
```

#### QRY-114 — Stock location inventory report

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry114StockLocationInventoryReportTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry114StockLocationInventoryReportTest.php
```

#### QRY-115 — Inventory and sales health report

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry115InventoryAndSalesHealthReportTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry115InventoryAndSalesHealthReportTest.php
```

#### QRY-116 — Categories with product count

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry116CategoriesWithProductCountTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry116CategoriesWithProductCountTest.php
```

#### QRY-117 — Variants stocked at multiple locations

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry117VariantsStockedAtMultipleLocationsTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry117VariantsStockedAtMultipleLocationsTest.php
```

#### QRY-118 — Inventory movement summary by type

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry118InventoryMovementSummaryByTypeTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry118InventoryMovementSummaryByTypeTest.php
```

#### QRY-119 — Sales order status summary

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry119SalesOrderStatusSummaryTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry119SalesOrderStatusSummaryTest.php
```

#### QRY-120 — Organization commerce report

```bash
# Uitvoeren via de testclass
php artisan test --filter=Qry120OrganizationCommerceReportTest

# Het exacte testbestand uitvoeren
php artisan test tests/Feature/QueryTickets/PhaseFour/Qry120OrganizationCommerceReportTest.php
```

## Belangrijke mappen

```text
app/Models/
    Eloquent-modellen en relaties

app/QueryTickets/
    De oefeningen die je oplost

database/factories/
    Factories voor geldige testdata

database/migrations/
    Database- en PostgreSQL-constraints

database/seeders/
    De vaste datasets per fase

docs/database/
    Database-blueprints en fasebeschrijvingen

tests/Feature/Data/
    Tests voor factories, relaties en seedscenario's

tests/Feature/QueryTickets/
    Exacte tests voor alle Query Tickets
```

## Werken in een persoonlijke oefenomgeving

De publieke repository bevat de lege oefeningen. Maak voor je eigen voortgang bij voorkeur een persoonlijke branch of een aparte clone:

```bash
git clone https://github.com/PascalVuong/QueryAutism.git query-autism-exercises
cd query-autism-exercises
git checkout -b learning/query-progress
```

Zo blijft de oorspronkelijke oefenrepository schoon en kun je al je oplossingen afzonderlijk committen.

## Waar begin je?

Begin bij:

```text
QRY-001 — Active users
```

QRY-001 bevat daarnaast een officiële voorbeeldoplossing waarmee je kunt bekijken hoe een Query Ticket, het resultaat en de test met elkaar samenwerken.

Daarna los je de tickets één voor één op. Begin met eenvoudige filters en werk uiteindelijk toe naar joins, subqueries, reconciliation-rapporten en PostgreSQL window functions.
