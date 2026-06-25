<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            ADD CONSTRAINT users_status_check
            CHECK (status IN ('active', 'pending', 'suspended', 'inactive'))
        ");

        DB::statement("
            ALTER TABLE user_profiles
            ADD CONSTRAINT user_profiles_contact_method_check
            CHECK (
                preferred_contact_method IN ('email', 'phone', 'sms', 'none')
            )
        ");

        DB::statement("
            ALTER TABLE organizations
            ADD CONSTRAINT organizations_status_check
            CHECK (status IN ('trial', 'active', 'suspended', 'closed'))
        ");

        DB::statement("
            ALTER TABLE organizations
            ADD CONSTRAINT organizations_parent_not_self_check
            CHECK (parent_id IS NULL OR parent_id <> id)
        ");

        DB::statement("
            ALTER TABLE organization_users
            ADD CONSTRAINT organization_users_status_check
            CHECK (status IN ('invited', 'active', 'suspended', 'left'))
        ");

        DB::statement("
            ALTER TABLE organization_users
            ADD CONSTRAINT organization_users_role_check
            CHECK (
                role IN (
                    'administrator',
                    'manager',
                    'employee',
                    'finance',
                    'reception'
                )
            )
        ");

        DB::statement("
            ALTER TABLE customers
            ADD CONSTRAINT customers_status_check
            CHECK (status IN ('active', 'inactive', 'blocked', 'archived'))
        ");

        DB::statement("
            ALTER TABLE customers
            ADD CONSTRAINT customers_source_check
            CHECK (
                source IN (
                    'staff',
                    'online',
                    'import',
                    'integration',
                    'guest'
                )
            )
        ");

        DB::statement("
            CREATE UNIQUE INDEX customers_organization_user_unique
            ON customers (organization_id, user_id)
            WHERE user_id IS NOT NULL
              AND deleted_at IS NULL
        ");

        DB::statement("
            ALTER TABLE customer_profiles
            ADD CONSTRAINT customer_profiles_loyalty_tier_check
            CHECK (
                loyalty_tier IN (
                    'none',
                    'bronze',
                    'silver',
                    'gold',
                    'platinum'
                )
            )
        ");

        DB::statement("
            ALTER TABLE customer_profiles
            ADD CONSTRAINT customer_profiles_risk_score_check
            CHECK (risk_score BETWEEN 0 AND 100)
        ");

        DB::statement("
            ALTER TABLE membership_plans
            ADD CONSTRAINT membership_plans_status_check
            CHECK (status IN ('draft', 'active', 'inactive', 'archived'))
        ");

        DB::statement("
            ALTER TABLE membership_plans
            ADD CONSTRAINT membership_plans_billing_interval_check
            CHECK (
                billing_interval IN (
                    'none',
                    'monthly',
                    'quarterly',
                    'yearly'
                )
            )
        ");

        DB::statement("
            ALTER TABLE membership_plans
            ADD CONSTRAINT membership_plans_price_check
            CHECK (price >= 0)
        ");

        DB::statement("
            ALTER TABLE membership_plans
            ADD CONSTRAINT membership_plans_valid_dates_check
            CHECK (
                valid_until IS NULL
                OR valid_from IS NULL
                OR valid_until >= valid_from
            )
        ");

        DB::statement("
            ALTER TABLE memberships
            ADD CONSTRAINT memberships_status_check
            CHECK (
                status IN (
                    'pending',
                    'active',
                    'paused',
                    'cancelled',
                    'expired'
                )
            )
        ");

        DB::statement("
            ALTER TABLE memberships
            ADD CONSTRAINT memberships_agreed_price_check
            CHECK (agreed_price >= 0)
        ");

        DB::statement("
            ALTER TABLE memberships
            ADD CONSTRAINT memberships_valid_dates_check
            CHECK (ends_at IS NULL OR ends_at >= starts_at)
        ");

        DB::statement("
            ALTER TABLE membership_status_histories
            ADD CONSTRAINT membership_status_histories_from_status_check
            CHECK (
                from_status IS NULL
                OR from_status IN (
                    'pending',
                    'active',
                    'paused',
                    'cancelled',
                    'expired'
                )
            )
        ");

        DB::statement("
            ALTER TABLE membership_status_histories
            ADD CONSTRAINT membership_status_histories_to_status_check
            CHECK (
                to_status IN (
                    'pending',
                    'active',
                    'paused',
                    'cancelled',
                    'expired'
                )
            )
        ");

        DB::statement("
            CREATE INDEX membership_status_histories_effective_index
            ON membership_status_histories (
                membership_id,
                effective_at DESC
            )
        ");

        DB::statement("
            ALTER TABLE external_identifiers
            ADD CONSTRAINT external_identifiers_verification_source_check
            CHECK (
                verification_source IS NULL
                OR verification_source IN ('manual', 'api', 'import')
            )
        ");

        DB::statement("
            CREATE UNIQUE INDEX external_identifiers_verified_unique
            ON external_identifiers (
                organization_id,
                provider,
                identifier_type,
                normalized_value
            )
            WHERE verified_at IS NOT NULL
              AND deleted_at IS NULL
        ");
    }

    public function down(): void
    {
        DB::statement(
            'DROP INDEX IF EXISTS external_identifiers_verified_unique'
        );
        DB::statement(
            'ALTER TABLE external_identifiers
             DROP CONSTRAINT IF EXISTS
             external_identifiers_verification_source_check'
        );

        DB::statement(
            'DROP INDEX IF EXISTS
             membership_status_histories_effective_index'
        );
        DB::statement(
            'ALTER TABLE membership_status_histories
             DROP CONSTRAINT IF EXISTS
             membership_status_histories_to_status_check'
        );
        DB::statement(
            'ALTER TABLE membership_status_histories
             DROP CONSTRAINT IF EXISTS
             membership_status_histories_from_status_check'
        );

        DB::statement(
            'ALTER TABLE memberships
             DROP CONSTRAINT IF EXISTS memberships_valid_dates_check'
        );
        DB::statement(
            'ALTER TABLE memberships
             DROP CONSTRAINT IF EXISTS memberships_agreed_price_check'
        );
        DB::statement(
            'ALTER TABLE memberships
             DROP CONSTRAINT IF EXISTS memberships_status_check'
        );

        DB::statement(
            'ALTER TABLE membership_plans
             DROP CONSTRAINT IF EXISTS membership_plans_valid_dates_check'
        );
        DB::statement(
            'ALTER TABLE membership_plans
             DROP CONSTRAINT IF EXISTS membership_plans_price_check'
        );
        DB::statement(
            'ALTER TABLE membership_plans
             DROP CONSTRAINT IF EXISTS
             membership_plans_billing_interval_check'
        );
        DB::statement(
            'ALTER TABLE membership_plans
             DROP CONSTRAINT IF EXISTS membership_plans_status_check'
        );

        DB::statement(
            'ALTER TABLE customer_profiles
             DROP CONSTRAINT IF EXISTS customer_profiles_risk_score_check'
        );
        DB::statement(
            'ALTER TABLE customer_profiles
             DROP CONSTRAINT IF EXISTS
             customer_profiles_loyalty_tier_check'
        );

        DB::statement(
            'DROP INDEX IF EXISTS customers_organization_user_unique'
        );
        DB::statement(
            'ALTER TABLE customers
             DROP CONSTRAINT IF EXISTS customers_source_check'
        );
        DB::statement(
            'ALTER TABLE customers
             DROP CONSTRAINT IF EXISTS customers_status_check'
        );

        DB::statement(
            'ALTER TABLE organization_users
             DROP CONSTRAINT IF EXISTS organization_users_role_check'
        );
        DB::statement(
            'ALTER TABLE organization_users
             DROP CONSTRAINT IF EXISTS organization_users_status_check'
        );

        DB::statement(
            'ALTER TABLE organizations
             DROP CONSTRAINT IF EXISTS organizations_parent_not_self_check'
        );
        DB::statement(
            'ALTER TABLE organizations
             DROP CONSTRAINT IF EXISTS organizations_status_check'
        );

        DB::statement(
            'ALTER TABLE user_profiles
             DROP CONSTRAINT IF EXISTS user_profiles_contact_method_check'
        );
        DB::statement(
            'ALTER TABLE users
             DROP CONSTRAINT IF EXISTS users_status_check'
        );
    }
};
