<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            ALTER TABLE venues
            ADD CONSTRAINT venues_status_check
            CHECK (status IN ('active', 'inactive', 'closed'))
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE facilities
            ADD CONSTRAINT facilities_status_check
            CHECK (status IN ('active', 'inactive', 'maintenance'))
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE resources
            ADD CONSTRAINT resources_status_check
            CHECK (status IN ('active', 'inactive', 'maintenance'))
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE resources
            ADD CONSTRAINT resources_capacity_check
            CHECK (capacity >= 1)
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE resource_availability_blocks
            ADD CONSTRAINT resource_blocks_type_check
            CHECK (type IN ('unavailable', 'maintenance', 'private'))
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE resource_availability_blocks
            ADD CONSTRAINT resource_blocks_period_check
            CHECK (ends_at > starts_at)
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservations
            ADD CONSTRAINT reservations_status_check
            CHECK (
                status IN (
                    'pending',
                    'confirmed',
                    'cancelled',
                    'completed',
                    'no_show'
                )
            )
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservations
            ADD CONSTRAINT reservations_period_check
            CHECK (ends_at > starts_at)
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservations
            ADD CONSTRAINT reservations_party_size_check
            CHECK (party_size >= 1)
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservations
            ADD CONSTRAINT reservations_amounts_check
            CHECK (
                subtotal >= 0
                AND discount_total >= 0
                AND total >= 0
            )
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservation_items
            ADD CONSTRAINT reservation_items_status_check
            CHECK (status IN ('reserved', 'cancelled', 'completed'))
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservation_items
            ADD CONSTRAINT reservation_items_period_check
            CHECK (ends_at > starts_at)
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservation_items
            ADD CONSTRAINT reservation_items_amounts_check
            CHECK (
                quantity >= 1
                AND unit_price >= 0
                AND total_price >= 0
            )
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservation_participants
            ADD CONSTRAINT reservation_participants_role_check
            CHECK (role IN ('booker', 'guest'))
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservation_participants
            ADD CONSTRAINT reservation_participants_status_check
            CHECK (
                status IN (
                    'registered',
                    'cancelled',
                    'attended',
                    'no_show'
                )
            )
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservation_status_histories
            ADD CONSTRAINT reservation_histories_from_status_check
            CHECK (
                from_status IS NULL
                OR from_status IN (
                    'pending',
                    'confirmed',
                    'cancelled',
                    'completed',
                    'no_show'
                )
            )
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE reservation_status_histories
            ADD CONSTRAINT reservation_histories_to_status_check
            CHECK (
                to_status IN (
                    'pending',
                    'confirmed',
                    'cancelled',
                    'completed',
                    'no_show'
                )
            )
        SQL);
    }

    public function down(): void
    {
        DB::statement(<<<'SQL'
            ALTER TABLE reservation_status_histories
            DROP CONSTRAINT IF EXISTS reservation_histories_to_status_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE reservation_status_histories
            DROP CONSTRAINT IF EXISTS reservation_histories_from_status_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE reservation_participants
            DROP CONSTRAINT IF EXISTS reservation_participants_status_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE reservation_participants
            DROP CONSTRAINT IF EXISTS reservation_participants_role_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE reservation_items
            DROP CONSTRAINT IF EXISTS reservation_items_amounts_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE reservation_items
            DROP CONSTRAINT IF EXISTS reservation_items_period_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE reservation_items
            DROP CONSTRAINT IF EXISTS reservation_items_status_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE reservations
            DROP CONSTRAINT IF EXISTS reservations_amounts_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE reservations
            DROP CONSTRAINT IF EXISTS reservations_party_size_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE reservations
            DROP CONSTRAINT IF EXISTS reservations_period_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE reservations
            DROP CONSTRAINT IF EXISTS reservations_status_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE resource_availability_blocks
            DROP CONSTRAINT IF EXISTS resource_blocks_period_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE resource_availability_blocks
            DROP CONSTRAINT IF EXISTS resource_blocks_type_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE resources
            DROP CONSTRAINT IF EXISTS resources_capacity_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE resources
            DROP CONSTRAINT IF EXISTS resources_status_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE facilities
            DROP CONSTRAINT IF EXISTS facilities_status_check
        SQL);
        DB::statement(<<<'SQL'
            ALTER TABLE venues
            DROP CONSTRAINT IF EXISTS venues_status_check
        SQL);
    }
};
