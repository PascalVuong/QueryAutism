# QueryAutism — Phase 1 Blueprint

## Identity, organizations, customers and memberships

QueryAutism is a multi-tenant SaaS platform for leisure and venue operations.

This first phase establishes the identity and membership layer used by later modules such as venues, facilities, resources, reservations, pricing, credits, passes, payments and events.

No factories or seeders should be generated until this schema is approved.

---

# Design rules

1. Every customer belongs to exactly one organization.
2. A customer may exist without a login account.
3. A user may belong to multiple organizations.
4. A user may represent one customer per organization.
5. Organizations may form a parent-child hierarchy.
6. Membership plans belong to one organization.
7. Memberships belong to one customer and one membership plan.
8. Memberships store a current status snapshot and an immutable status history.
9. External identifiers may be duplicated while unverified.
10. A verified external identifier must be unique within an organization, provider and identifier type.
11. Main business tables use soft deletes.
12. History tables are immutable and do not use soft deletes.

---

# 1. users

## Purpose

Stores login accounts. A user can be a platform user, organization employee, customer account or any combination of those roles.

## Columns

| Column | PostgreSQL type | Nullable | Notes |
|---|---|---:|---|
| `id` | `bigint` | No | Primary key |
| `uuid` | `uuid` | No | Public identifier |
| `name` | `varchar(255)` | No | Display name |
| `email` | `varchar(255)` | No | Login email |
| `email_verified_at` | `timestamptz` | Yes | Null means unverified |
| `password` | `varchar(255)` | No | Hashed password |
| `status` | `varchar(30)` | No | `active`, `pending`, `suspended`, `inactive` |
| `locale` | `varchar(10)` | No | Default `en` |
| `timezone` | `varchar(100)` | No | Default `Europe/Amsterdam` |
| `last_login_at` | `timestamptz` | Yes | Latest successful login |
| `last_login_ip` | `inet` | Yes | PostgreSQL IP type |
| `remember_token` | `varchar(100)` | Yes | Laravel remember token |
| `created_at` | `timestamptz` | No | Laravel timestamp |
| `updated_at` | `timestamptz` | No | Laravel timestamp |
| `deleted_at` | `timestamptz` | Yes | Soft delete |

## Constraints and indexes

- Primary key on `id`
- Unique index on `uuid`
- Unique index on `email`
- Index on `status`
- Index on `email_verified_at`
- Index on `deleted_at`
- Check constraint for allowed `status` values

## Relationships

- `hasOne(UserProfile::class)`
- `belongsToMany(Organization::class)->using(OrganizationUser::class)`
- `hasMany(Customer::class)`
- `hasManyThrough(CustomerProfile::class, Customer::class)`

## Seed scenarios

- active verified users;
- active unverified users;
- suspended users;
- users without profiles;
- users without organizations;
- users belonging to multiple organizations;
- soft-deleted users with existing historical relationships.

---

# 2. user_profiles

## Purpose

Stores optional personal and preference information for a user.

## Columns

| Column | PostgreSQL type | Nullable | Notes |
|---|---|---:|---|
| `id` | `bigint` | No | Primary key |
| `user_id` | `bigint` | No | One profile per user |
| `first_name` | `varchar(100)` | No | Given name |
| `last_name` | `varchar(150)` | No | Family name |
| `phone` | `varchar(30)` | Yes | Contact number |
| `date_of_birth` | `date` | Yes | Optional |
| `avatar_path` | `varchar(500)` | Yes | Stored file path |
| `preferred_contact_method` | `varchar(30)` | No | `email`, `phone`, `sms`, `none` |
| `marketing_consent` | `boolean` | No | Default false |
| `preferences` | `jsonb` | Yes | Interface and notification preferences |
| `created_at` | `timestamptz` | No | Laravel timestamp |
| `updated_at` | `timestamptz` | No | Laravel timestamp |

## Constraints and indexes

- Unique foreign key on `user_id`
- Cascade on hard deletion of the user
- Check constraint for `preferred_contact_method`
- GIN index on `preferences` will be added in the PostgreSQL index lab, not initially

## Relationships

- `belongsTo(User::class)`

## Seed scenarios

- users with complete profiles;
- users without profiles;
- profiles without a phone number;
- profiles with and without marketing consent;
- JSON preferences with different notification settings.

---

# 3. organizations

## Purpose

Represents tenants that use the SaaS platform. Examples include golf clubs, wellness groups, event companies and sport centres.

## Columns

| Column | PostgreSQL type | Nullable | Notes |
|---|---|---:|---|
| `id` | `bigint` | No | Primary key |
| `uuid` | `uuid` | No | Public identifier |
| `parent_id` | `bigint` | Yes | Parent organization |
| `name` | `varchar(255)` | No | Display name |
| `legal_name` | `varchar(255)` | Yes | Registered legal name |
| `slug` | `varchar(255)` | No | Public slug |
| `registration_number` | `varchar(100)` | Yes | Chamber/company number |
| `vat_number` | `varchar(100)` | Yes | Tax identifier |
| `email` | `varchar(255)` | Yes | General contact |
| `phone` | `varchar(30)` | Yes | General contact |
| `status` | `varchar(30)` | No | `trial`, `active`, `suspended`, `closed` |
| `timezone` | `varchar(100)` | No | Default `Europe/Amsterdam` |
| `currency` | `char(3)` | No | Default `EUR` |
| `country_code` | `char(2)` | No | ISO country code |
| `settings` | `jsonb` | Yes | Tenant-level settings |
| `onboarded_at` | `timestamptz` | Yes | Completed onboarding |
| `created_at` | `timestamptz` | No | Laravel timestamp |
| `updated_at` | `timestamptz` | No | Laravel timestamp |
| `deleted_at` | `timestamptz` | Yes | Soft delete |

## Constraints and indexes

- Unique index on `uuid`
- Unique index on `slug`
- Foreign key `parent_id` references `organizations.id`
- `parent_id` becomes null if parent is hard deleted
- Index on `parent_id`
- Index on `status`
- Index on `country_code`
- Check constraint for allowed `status` values
- Check constraint preventing `parent_id = id`

## Relationships

- `belongsTo(Organization::class, 'parent_id')`
- `hasMany(Organization::class, 'parent_id')`
- `belongsToMany(User::class)->using(OrganizationUser::class)`
- `hasMany(Customer::class)`
- later: `hasMany(Venue::class)`

## Seed scenarios

- independent organizations;
- parent companies with child organizations;
- active and suspended organizations;
- trial organizations without customers;
- closed organizations with historical customers;
- organizations without active users.

---

# 4. organization_users

## Purpose

Pivot model connecting users to organizations.

## Columns

| Column | PostgreSQL type | Nullable | Notes |
|---|---|---:|---|
| `id` | `bigint` | No | Primary key |
| `organization_id` | `bigint` | No | Tenant |
| `user_id` | `bigint` | No | Member user |
| `role` | `varchar(50)` | No | Temporary phase-1 role |
| `status` | `varchar(30)` | No | `invited`, `active`, `suspended`, `left` |
| `is_owner` | `boolean` | No | Default false |
| `invited_by_user_id` | `bigint` | Yes | Inviting user |
| `invited_at` | `timestamptz` | Yes | Invitation timestamp |
| `joined_at` | `timestamptz` | Yes | Membership started |
| `left_at` | `timestamptz` | Yes | Membership ended |
| `created_at` | `timestamptz` | No | Laravel timestamp |
| `updated_at` | `timestamptz` | No | Laravel timestamp |

## Constraints and indexes

- Unique index on `organization_id, user_id`
- Foreign key to organization with cascade on hard delete
- Foreign key to user with cascade on hard delete
- `invited_by_user_id` becomes null when inviter is hard deleted
- Index on `organization_id, status`
- Index on `user_id, status`
- Index on `role`
- Check constraints for `status` and phase-1 role values

## Relationships

Use a custom pivot model:

- `OrganizationUser extends Pivot`
- Organization `belongsToMany(User::class)->using(OrganizationUser::class)`
- User `belongsToMany(Organization::class)->using(OrganizationUser::class)`

## Seed scenarios

- active owners;
- active staff;
- invited users who never joined;
- suspended members;
- members who left;
- users belonging to multiple organizations;
- organizations with no active members.

## Query topics

- `withPivot`
- `wherePivot`
- `wherePivotIn`
- `orderByPivot`
- `attach`
- `detach`
- `sync`
- `syncWithoutDetaching`
- `updateExistingPivot`

---

# 5. customers

## Purpose

Represents customers of one organization. A customer can exist without a login account.

## Columns

| Column | PostgreSQL type | Nullable | Notes |
|---|---|---:|---|
| `id` | `bigint` | No | Primary key |
| `uuid` | `uuid` | No | Public identifier |
| `organization_id` | `bigint` | No | Owning tenant |
| `user_id` | `bigint` | Yes | Optional login account |
| `customer_number` | `varchar(50)` | No | Unique within organization |
| `first_name` | `varchar(100)` | No | Snapshot/contact data |
| `last_name` | `varchar(150)` | No | Snapshot/contact data |
| `email` | `varchar(255)` | Yes | Not globally unique |
| `phone` | `varchar(30)` | Yes | Optional |
| `date_of_birth` | `date` | Yes | Optional |
| `status` | `varchar(30)` | No | `active`, `inactive`, `blocked`, `archived` |
| `source` | `varchar(50)` | No | `staff`, `online`, `import`, `integration`, `guest` |
| `marketing_consent` | `boolean` | No | Default false |
| `registered_at` | `timestamptz` | Yes | Business registration date |
| `last_activity_at` | `timestamptz` | Yes | Last known activity |
| `notes` | `text` | Yes | Internal notes |
| `created_at` | `timestamptz` | No | Laravel timestamp |
| `updated_at` | `timestamptz` | No | Laravel timestamp |
| `deleted_at` | `timestamptz` | Yes | Soft delete |

## Constraints and indexes

- Unique index on `uuid`
- Unique index on `organization_id, customer_number`
- Unique index on `organization_id, user_id` where `user_id` is not null
- Foreign key to organization with restrict on hard delete
- Foreign key to user with null on hard delete
- Index on `organization_id, status`
- Index on `organization_id, email`
- Index on `organization_id, last_activity_at`
- Index on `source`
- Check constraints for `status` and `source`

## Relationships

- `belongsTo(Organization::class)`
- `belongsTo(User::class)`
- `hasOne(CustomerProfile::class)`
- `hasMany(Membership::class)`
- `hasMany(ExternalIdentifier::class)`
- later: `hasMany(Reservation::class)`

## Seed scenarios

- customers with user accounts;
- guest customers without accounts;
- customers with an unverified user account;
- inactive and blocked customers;
- multiple guest records sharing the same email;
- customers in different organizations linked to the same user;
- customers with no membership;
- soft-deleted customers with historical membership data.

---

# 6. customer_profiles

## Purpose

Stores denormalized customer statistics and preferences. This creates useful comparison and consistency exercises later.

## Columns

| Column | PostgreSQL type | Nullable | Notes |
|---|---|---:|---|
| `id` | `bigint` | No | Primary key |
| `customer_id` | `bigint` | No | One profile per customer |
| `preferred_language` | `varchar(10)` | No | Default `en` |
| `preferred_timezone` | `varchar(100)` | No | Default organization timezone |
| `preferred_currency` | `char(3)` | No | Default organization currency |
| `average_booking_value` | `numeric(12,2)` | No | Snapshot statistic |
| `total_reservations` | `integer` | No | Snapshot statistic |
| `total_spent` | `numeric(14,2)` | No | Snapshot statistic |
| `no_show_count` | `integer` | No | Snapshot statistic |
| `cancellation_count` | `integer` | No | Snapshot statistic |
| `loyalty_tier` | `varchar(30)` | No | `none`, `bronze`, `silver`, `gold`, `platinum` |
| `risk_score` | `numeric(5,2)` | No | Range 0–100 |
| `preferences` | `jsonb` | Yes | Booking and communication preferences |
| `last_recalculated_at` | `timestamptz` | Yes | Snapshot refresh time |
| `created_at` | `timestamptz` | No | Laravel timestamp |
| `updated_at` | `timestamptz` | No | Laravel timestamp |

## Constraints and indexes

- Unique foreign key on `customer_id`
- Cascade on hard deletion of customer
- Index on `loyalty_tier`
- Index on `risk_score`
- Check constraint `risk_score between 0 and 100`
- Check constraint for allowed loyalty tiers

## Relationships

- `belongsTo(Customer::class)`
- a User can reach multiple customer profiles through `hasManyThrough(CustomerProfile::class, Customer::class)`

## Seed scenarios

- profiles whose stored totals match real data;
- profiles with stale totals;
- high-risk customers;
- profiles with JSON preferences;
- customers without profiles.

---

# 7. membership_plans

## Purpose

Defines membership types offered by one organization.

## Columns

| Column | PostgreSQL type | Nullable | Notes |
|---|---|---:|---|
| `id` | `bigint` | No | Primary key |
| `uuid` | `uuid` | No | Public identifier |
| `organization_id` | `bigint` | No | Owning tenant |
| `code` | `varchar(50)` | No | Unique within organization |
| `name` | `varchar(150)` | No | Display name |
| `description` | `text` | Yes | Plan description |
| `status` | `varchar(30)` | No | `draft`, `active`, `inactive`, `archived` |
| `billing_interval` | `varchar(30)` | No | `none`, `monthly`, `quarterly`, `yearly` |
| `price` | `numeric(12,2)` | No | Default price |
| `currency` | `char(3)` | No | ISO currency |
| `booking_window_days` | `integer` | No | Days bookable ahead |
| `max_active_reservations` | `integer` | Yes | Null means unlimited |
| `max_guests_per_reservation` | `integer` | Yes | Null means unlimited |
| `priority` | `integer` | No | Higher priority wins |
| `valid_from` | `date` | Yes | Optional plan availability |
| `valid_until` | `date` | Yes | Optional plan availability |
| `settings` | `jsonb` | Yes | Future entitlement settings |
| `created_at` | `timestamptz` | No | Laravel timestamp |
| `updated_at` | `timestamptz` | No | Laravel timestamp |
| `deleted_at` | `timestamptz` | Yes | Soft delete |

## Constraints and indexes

- Unique index on `uuid`
- Unique index on `organization_id, code`
- Foreign key to organization with restrict on hard delete
- Index on `organization_id, status`
- Index on `priority`
- Index on `valid_from, valid_until`
- Check constraints for status and billing interval
- Check constraint `price >= 0`
- Check constraint `valid_until >= valid_from` when both exist

## Relationships

- `belongsTo(Organization::class)`
- `hasMany(Membership::class)`

## Seed scenarios

- free guest plans;
- monthly and yearly plans;
- active plans;
- expired plans;
- archived plans with historical memberships;
- overlapping validity periods;
- plans with booking limits;
- plans with unlimited fields set to null.

---

# 8. memberships

## Purpose

Connects customers to membership plans and stores the current membership snapshot.

## Columns

| Column | PostgreSQL type | Nullable | Notes |
|---|---|---:|---|
| `id` | `bigint` | No | Primary key |
| `uuid` | `uuid` | No | Public identifier |
| `organization_id` | `bigint` | No | Tenant scope |
| `customer_id` | `bigint` | No | Member customer |
| `membership_plan_id` | `bigint` | No | Selected plan |
| `membership_number` | `varchar(80)` | No | Unique within organization |
| `status` | `varchar(30)` | No | `pending`, `active`, `paused`, `cancelled`, `expired` |
| `starts_at` | `timestamptz` | No | Membership start |
| `ends_at` | `timestamptz` | Yes | Null means no fixed end |
| `activated_at` | `timestamptz` | Yes | Activation timestamp |
| `cancelled_at` | `timestamptz` | Yes | Cancellation timestamp |
| `cancellation_reason` | `text` | Yes | Optional |
| `auto_renew` | `boolean` | No | Default false |
| `agreed_price` | `numeric(12,2)` | No | Historical price snapshot |
| `currency` | `char(3)` | No | Historical currency snapshot |
| `created_at` | `timestamptz` | No | Laravel timestamp |
| `updated_at` | `timestamptz` | No | Laravel timestamp |
| `deleted_at` | `timestamptz` | Yes | Soft delete |

## Constraints and indexes

- Unique index on `uuid`
- Unique index on `organization_id, membership_number`
- Foreign key to organization with restrict on hard delete
- Foreign key to customer with restrict on hard delete
- Foreign key to membership plan with restrict on hard delete
- Index on `organization_id, status`
- Index on `customer_id, status`
- Index on `membership_plan_id, status`
- Index on `starts_at, ends_at`
- Check constraint for allowed status
- Check constraint `agreed_price >= 0`
- Check constraint `ends_at >= starts_at` when `ends_at` exists

## Relationships

- `belongsTo(Organization::class)`
- `belongsTo(Customer::class)`
- `belongsTo(MembershipPlan::class)`
- `hasMany(MembershipStatusHistory::class)`
- `hasOne(...)->latestOfMany()` for latest created history
- `hasOne(...)->ofMany('effective_at', 'max')` for effective status

## Seed scenarios

- active memberships;
- future pending memberships;
- expired memberships;
- paused memberships;
- cancelled memberships;
- customers with multiple historical memberships;
- overlapping memberships;
- current status inconsistent with the latest history;
- memberships linked to a plan from the wrong organization for validation tests;
- different agreed prices for the same plan.

---

# 9. membership_status_histories

## Purpose

Immutable record of membership status changes.

## Columns

| Column | PostgreSQL type | Nullable | Notes |
|---|---|---:|---|
| `id` | `bigint` | No | Primary key |
| `membership_id` | `bigint` | No | Related membership |
| `from_status` | `varchar(30)` | Yes | Null for initial status |
| `to_status` | `varchar(30)` | No | New status |
| `reason` | `text` | Yes | Human explanation |
| `changed_by_user_id` | `bigint` | Yes | User who caused change |
| `effective_at` | `timestamptz` | No | Business-effective timestamp |
| `metadata` | `jsonb` | Yes | Additional change context |
| `created_at` | `timestamptz` | No | Insert timestamp |

## Constraints and indexes

- Foreign key to membership with cascade on hard delete
- `changed_by_user_id` becomes null when user is hard deleted
- Index on `membership_id, effective_at desc`
- Index on `to_status`
- Index on `effective_at`
- Check constraints for `from_status` and `to_status`
- No `updated_at`
- No soft deletes

## Relationships

- `belongsTo(Membership::class)`
- `belongsTo(User::class, 'changed_by_user_id')`

## Seed scenarios

- normal status progressions;
- multiple history rows at the same timestamp;
- backdated changes;
- current membership status differing from latest effective history;
- changes created by deleted users;
- memberships without status history.

---

# 10. external_identifiers

## Purpose

Stores third-party identifiers for customers, such as federation numbers, external CRM numbers, loyalty IDs or integration IDs.

## Columns

| Column | PostgreSQL type | Nullable | Notes |
|---|---|---:|---|
| `id` | `bigint` | No | Primary key |
| `organization_id` | `bigint` | No | Tenant scope |
| `customer_id` | `bigint` | No | Related customer |
| `provider` | `varchar(100)` | No | Example `golf_federation` |
| `identifier_type` | `varchar(100)` | No | Example `membership_number` |
| `identifier_value` | `varchar(255)` | No | Original supplied value |
| `normalized_value` | `varchar(255)` | No | Trimmed/case-normalized value |
| `verified_at` | `timestamptz` | Yes | Null means unverified |
| `verification_source` | `varchar(50)` | Yes | `manual`, `api`, `import` |
| `last_checked_at` | `timestamptz` | Yes | Latest verification check |
| `metadata` | `jsonb` | Yes | Provider-specific details |
| `created_at` | `timestamptz` | No | Laravel timestamp |
| `updated_at` | `timestamptz` | No | Laravel timestamp |
| `deleted_at` | `timestamptz` | Yes | Soft delete |

## Constraints and indexes

- Foreign key to organization with restrict on hard delete
- Foreign key to customer with cascade on hard delete
- Index on `organization_id, customer_id`
- Index on `organization_id, provider, identifier_type`
- Index on `normalized_value`
- Partial unique index:

```sql
CREATE UNIQUE INDEX external_identifiers_verified_unique
ON external_identifiers (
    organization_id,
    provider,
    identifier_type,
    normalized_value
)
WHERE verified_at IS NOT NULL
  AND deleted_at IS NULL;
```

This allows duplicate unverified values while preventing duplicate verified values.

## Relationships

- `belongsTo(Organization::class)`
- `belongsTo(Customer::class)`

## Seed scenarios

- verified identifiers;
- unverified identifiers;
- duplicate unverified identifiers;
- the same value in different organizations;
- the same value for different providers;
- soft-deleted verified identifiers;
- identifiers with leading spaces or inconsistent casing before normalization.

---

# Relationship map

```text
User
├── hasOne UserProfile
├── belongsToMany Organizations
├── hasMany Customers
└── hasManyThrough CustomerProfiles

Organization
├── belongsTo parent Organization
├── hasMany child Organizations
├── belongsToMany Users
├── hasMany Customers
├── hasMany MembershipPlans
├── hasMany Memberships
└── hasMany ExternalIdentifiers

Customer
├── belongsTo Organization
├── belongsTo User nullable
├── hasOne CustomerProfile
├── hasMany Memberships
└── hasMany ExternalIdentifiers

MembershipPlan
├── belongsTo Organization
└── hasMany Memberships

Membership
├── belongsTo Organization
├── belongsTo Customer
├── belongsTo MembershipPlan
├── hasMany MembershipStatusHistories
├── hasOne latestStatusHistory
└── hasOne effectiveStatusHistory
```

---

# Query coverage from phase 1

## Eloquent basics

- `where`
- `orWhere`
- grouped conditions
- `whereIn`
- `whereNotIn`
- `whereBetween`
- `whereNull`
- `whereNotNull`
- date filters
- `when`
- `unless`
- `orderBy`
- `latest`
- `oldest`
- `exists`
- `doesntExist`

## Relationship queries

- `with`
- nested eager loading
- constrained eager loading
- `has`
- `doesntHave`
- `whereHas`
- `whereDoesntHave`
- `whereRelation`
- `withWhereHas`
- `withCount`
- `withExists`
- `withSum`
- `latestOfMany`
- `ofMany`
- many-to-many pivot filters
- self-referencing relations
- `hasManyThrough`

## Raw SQL

- `INNER JOIN`
- `LEFT JOIN`
- self joins
- `EXISTS`
- `NOT EXISTS`
- scalar subqueries
- correlated subqueries
- `GROUP BY`
- `HAVING`
- `CASE WHEN`
- CTEs
- recursive CTEs
- partial indexes
- JSONB filters
- date overlap checks
- duplicate detection

---

# First 20 query tickets

## QRY-001

Return all active users ordered by latest login.

## QRY-002

Return users who have never logged in.

## QRY-003

Return users without a profile.

## QRY-004

Return users belonging to more than one organization.

## QRY-005

Return organizations without active users.

## QRY-006

Return organizations and the number of active users on their pivot membership.

## QRY-007

Return customers without a user account.

## QRY-008

Return customers with an active and verified user account.

## QRY-009

Return customers whose email differs from their linked user email.

## QRY-010

Return organizations with more guest customers than account-linked customers.

## QRY-011

Return customers with no customer profile.

## QRY-012

Return customers whose stored profile totals have not been recalculated in the last 30 days.

## QRY-013

Return active membership plans valid on a supplied date.

## QRY-014

Return customers with at least one active membership.

## QRY-015

Return customers without any active membership.

## QRY-016

Return memberships whose stored status differs from the latest effective status history.

## QRY-017

Return customers with overlapping memberships.

## QRY-018

Return verified external identifiers for active customers.

## QRY-019

Return duplicate unverified external identifiers within one organization.

## QRY-020

Return the organization hierarchy with each organization's depth and full path using a recursive CTE.

---

# Migration order

Migrations must be created in this exact dependency order:

1. `users`
2. `user_profiles`
3. `organizations`
4. `organization_users`
5. `customers`
6. `customer_profiles`
7. `membership_plans`
8. `memberships`
9. `membership_status_histories`
10. `external_identifiers`
11. PostgreSQL partial indexes and check constraints that require raw SQL

---

# Approval checklist

Before generating migrations, confirm:

- customers must always belong to an organization;
- customer-to-user is optional;
- one user may have one customer record per organization;
- roles remain a pivot string in phase 1;
- organizations may have a parent organization;
- membership status exists both as snapshot and history;
- duplicate unverified identifiers are allowed;
- verified identifiers are unique only within one organization/provider/type;
- PostgreSQL-specific types `uuid`, `jsonb`, `inet` and `timestamptz` are acceptable.
