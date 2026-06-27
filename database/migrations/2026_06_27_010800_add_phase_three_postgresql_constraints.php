<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE price_rules
            ADD CONSTRAINT price_rules_type_check
            CHECK (type IN ('fixed', 'percentage'))
        ");

        DB::statement("
            ALTER TABLE price_rules
            ADD CONSTRAINT price_rules_status_check
            CHECK (status IN ('draft', 'active', 'inactive', 'archived'))
        ");

        DB::statement("
            ALTER TABLE price_rules
            ADD CONSTRAINT price_rules_value_check
            CHECK (
                (
                    type = 'fixed'
                    AND amount IS NOT NULL
                    AND amount >= 0
                    AND percentage IS NULL
                )
                OR
                (
                    type = 'percentage'
                    AND percentage IS NOT NULL
                    AND percentage BETWEEN 0 AND 100
                    AND amount IS NULL
                )
            )
        ");

        DB::statement("
            ALTER TABLE price_rules
            ADD CONSTRAINT price_rules_period_check
            CHECK (
                ends_at IS NULL
                OR starts_at IS NULL
                OR ends_at >= starts_at
            )
        ");

        DB::statement("
            ALTER TABLE reservation_charges
            ADD CONSTRAINT reservation_charges_type_check
            CHECK (type IN ('base', 'surcharge', 'discount', 'tax'))
        ");

        DB::statement("
            ALTER TABLE reservation_charges
            ADD CONSTRAINT reservation_charges_direction_check
            CHECK (direction IN ('debit', 'credit'))
        ");

        DB::statement("
            ALTER TABLE reservation_charges
            ADD CONSTRAINT reservation_charges_amount_check
            CHECK (
                quantity > 0
                AND unit_amount >= 0
                AND total_amount >= 0
            )
        ");

        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT payments_status_check
            CHECK (
                status IN (
                    'pending',
                    'authorized',
                    'paid',
                    'partially_refunded',
                    'refunded',
                    'failed',
                    'cancelled'
                )
            )
        ");

        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT payments_method_check
            CHECK (
                method IN (
                    'card',
                    'cash',
                    'bank_transfer',
                    'credit'
                )
            )
        ");

        DB::statement("
            ALTER TABLE payments
            ADD CONSTRAINT payments_amount_check
            CHECK (
                amount >= 0
                AND refunded_amount >= 0
                AND refunded_amount <= amount
            )
        ");

        DB::statement("
            ALTER TABLE payment_transactions
            ADD CONSTRAINT payment_transactions_type_check
            CHECK (
                type IN (
                    'authorization',
                    'capture',
                    'refund',
                    'failure',
                    'void'
                )
            )
        ");

        DB::statement("
            ALTER TABLE payment_transactions
            ADD CONSTRAINT payment_transactions_status_check
            CHECK (status IN ('pending', 'succeeded', 'failed'))
        ");

        DB::statement("
            ALTER TABLE payment_transactions
            ADD CONSTRAINT payment_transactions_amount_check
            CHECK (amount >= 0)
        ");

        DB::statement("
            ALTER TABLE refunds
            ADD CONSTRAINT refunds_status_check
            CHECK (status IN ('pending', 'succeeded', 'failed'))
        ");

        DB::statement("
            ALTER TABLE refunds
            ADD CONSTRAINT refunds_amount_check
            CHECK (amount > 0)
        ");

        DB::statement("
            ALTER TABLE credit_accounts
            ADD CONSTRAINT credit_accounts_status_check
            CHECK (status IN ('active', 'frozen', 'closed'))
        ");

        DB::statement("
            ALTER TABLE credit_accounts
            ADD CONSTRAINT credit_accounts_balance_check
            CHECK (balance >= 0)
        ");

        DB::statement("
            ALTER TABLE credit_transactions
            ADD CONSTRAINT credit_transactions_type_check
            CHECK (
                type IN (
                    'grant',
                    'spend',
                    'refund',
                    'expire',
                    'adjustment'
                )
            )
        ");

        DB::statement("
            ALTER TABLE credit_transactions
            ADD CONSTRAINT credit_transactions_amount_check
            CHECK (amount <> 0)
        ");
    }

    public function down(): void
    {
        $constraints = [
            ['credit_transactions', 'credit_transactions_amount_check'],
            ['credit_transactions', 'credit_transactions_type_check'],
            ['credit_accounts', 'credit_accounts_balance_check'],
            ['credit_accounts', 'credit_accounts_status_check'],
            ['refunds', 'refunds_amount_check'],
            ['refunds', 'refunds_status_check'],
            ['payment_transactions', 'payment_transactions_amount_check'],
            ['payment_transactions', 'payment_transactions_status_check'],
            ['payment_transactions', 'payment_transactions_type_check'],
            ['payments', 'payments_amount_check'],
            ['payments', 'payments_method_check'],
            ['payments', 'payments_status_check'],
            ['reservation_charges', 'reservation_charges_amount_check'],
            ['reservation_charges', 'reservation_charges_direction_check'],
            ['reservation_charges', 'reservation_charges_type_check'],
            ['price_rules', 'price_rules_period_check'],
            ['price_rules', 'price_rules_value_check'],
            ['price_rules', 'price_rules_status_check'],
            ['price_rules', 'price_rules_type_check'],
        ];

        foreach ($constraints as [$table, $constraint]) {
            DB::statement(
                "ALTER TABLE {$table} "
                ."DROP CONSTRAINT IF EXISTS {$constraint}",
            );
        }
    }
};
