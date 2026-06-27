# QueryAutism

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
> ::: 

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
::: 