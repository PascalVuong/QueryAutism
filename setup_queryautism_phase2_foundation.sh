#!/usr/bin/env bash
set -euo pipefail

if [[ ! -f artisan ]]; then
    echo "Run this script from the QueryAutism project root." >&2
    exit 1
fi

required_files=(
    "database/seeders/DatabaseSeeder.php"
    "app/Models/Organization.php"
    "app/Models/Customer.php"
)

for required_file in "${required_files[@]}"; do
    if [[ ! -f "$required_file" ]]; then
        echo "Required file not found: $required_file" >&2
        exit 1
    fi
done

new_files=(
    "database/migrations/2026_06_27_000100_create_venues_table.php"
    "database/migrations/2026_06_27_000200_create_facilities_table.php"
    "database/migrations/2026_06_27_000300_create_resources_table.php"
    "database/migrations/2026_06_27_000400_create_resource_availability_blocks_table.php"
    "database/migrations/2026_06_27_000500_create_reservations_table.php"
    "database/migrations/2026_06_27_000600_create_reservation_items_table.php"
    "database/migrations/2026_06_27_000700_create_reservation_participants_table.php"
    "database/migrations/2026_06_27_000800_create_reservation_status_histories_table.php"
    "database/migrations/2026_06_27_000900_add_phase_two_postgresql_constraints.php"
    "app/Models/Venue.php"
    "app/Models/Facility.php"
    "app/Models/Resource.php"
    "app/Models/ResourceAvailabilityBlock.php"
    "app/Models/Reservation.php"
    "app/Models/ReservationItem.php"
    "app/Models/ReservationParticipant.php"
    "app/Models/ReservationStatusHistory.php"
    "database/factories/VenueFactory.php"
    "database/factories/FacilityFactory.php"
    "database/factories/ResourceFactory.php"
    "database/factories/ResourceAvailabilityBlockFactory.php"
    "database/factories/ReservationFactory.php"
    "database/factories/ReservationItemFactory.php"
    "database/factories/ReservationParticipantFactory.php"
    "database/factories/ReservationStatusHistoryFactory.php"
    "database/seeders/PhaseTwoScenarioSeeder.php"
    "tests/Feature/Data/PhaseTwoFactoriesTest.php"
    "tests/Feature/Data/PhaseTwoRelationshipsTest.php"
    "tests/Feature/Data/PhaseTwoScenarioSeederTest.php"
    "docs/database/queryautism-phase-2-reservations-resources.md"
)

for new_file in "${new_files[@]}"; do
    if [[ -e "$new_file" ]]; then
        echo "Refusing to overwrite existing file: $new_file" >&2
        exit 1
    fi
done

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

replaceOnce(
    'database/seeders/DatabaseSeeder.php',
    <<<'OLD'
        $this->call([
            PhaseOneScenarioSeeder::class,
        ]);
OLD,
    <<<'NEW'
        $this->call([
            PhaseOneScenarioSeeder::class,
            PhaseTwoScenarioSeeder::class,
        ]);
NEW,
);

replaceOnce(
    'app/Models/Organization.php',
    <<<'OLD'
    public function externalIdentifiers(): HasMany
    {
        return $this->hasMany(ExternalIdentifier::class);
    }

    /**
OLD,
    <<<'NEW'
    public function externalIdentifiers(): HasMany
    {
        return $this->hasMany(ExternalIdentifier::class);
    }

    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
NEW,
);

replaceOnce(
    'app/Models/Customer.php',
    <<<'OLD'
    public function externalIdentifiers(): HasMany
    {
        return $this->hasMany(ExternalIdentifier::class);
    }

    /**
OLD,
    <<<'NEW'
    public function externalIdentifiers(): HasMany
    {
        return $this->hasMany(ExternalIdentifier::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservationParticipants(): HasMany
    {
        return $this->hasMany(ReservationParticipant::class);
    }

    /**
NEW,
);
PHP

mkdir -p database/migrations
cat > database/migrations/2026_06_27_000100_create_venues_table.php <<'PHPFILE'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('status', 30)->default('active')->index();
            $table->string('timezone', 100)->default('Europe/Amsterdam');
            $table->string('address_line_1')->nullable();
            $table->string('city', 150)->nullable();
            $table->char('country_code', 2)->default('NL');
            $table->jsonb('settings')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique(['organization_id', 'slug']);
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
PHPFILE

mkdir -p database/migrations
cat > database/migrations/2026_06_27_000200_create_facilities_table.php <<'PHPFILE'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('venue_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('code', 50);
            $table->string('name');
            $table->string('type', 50);
            $table->string('status', 30)->default('active');
            $table->jsonb('settings')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique(['venue_id', 'code']);
            $table->index(['venue_id', 'status']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
PHPFILE

mkdir -p database/migrations
cat > database/migrations/2026_06_27_000300_create_resources_table.php <<'PHPFILE'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('facility_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('code', 50);
            $table->string('name');
            $table->string('type', 50);
            $table->string('status', 30)->default('active');
            $table->unsignedInteger('capacity')->default(1);
            $table->boolean('is_bookable')->default(true);
            $table->jsonb('settings')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique(['facility_id', 'code']);
            $table->index(['facility_id', 'status']);
            $table->index(['is_bookable', 'status']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
PHPFILE

mkdir -p database/migrations
cat > database/migrations/2026_06_27_000400_create_resource_availability_blocks_table.php <<'PHPFILE'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_availability_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('type', 30)->default('unavailable');
            $table->timestampTz('starts_at');
            $table->timestampTz('ends_at');
            $table->text('reason')->nullable();
            $table->foreignId('created_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->jsonb('metadata')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index([
                'resource_id',
                'starts_at',
                'ends_at',
            ], 'resource_blocks_period_index');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_availability_blocks');
    }
};
PHPFILE

mkdir -p database/migrations
cat > database/migrations/2026_06_27_000500_create_reservations_table.php <<'PHPFILE'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('venue_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('customer_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('created_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('reference_number', 80);
            $table->string('status', 30)->default('pending');
            $table->timestampTz('starts_at');
            $table->timestampTz('ends_at');
            $table->unsignedInteger('party_size')->default(1);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->char('currency', 3)->default('EUR');
            $table->text('notes')->nullable();
            $table->timestampTz('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->unique([
                'organization_id',
                'reference_number',
            ]);
            $table->index(['organization_id', 'status']);
            $table->index(['venue_id', 'status']);
            $table->index(['customer_id', 'starts_at']);
            $table->index(['starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
PHPFILE

mkdir -p database/migrations
cat > database/migrations/2026_06_27_000600_create_reservation_items_table.php <<'PHPFILE'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('resource_id')
                ->constrained()
                ->restrictOnDelete();
            $table->timestampTz('starts_at');
            $table->timestampTz('ends_at');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('status', 30)->default('reserved');
            $table->text('notes')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index([
                'resource_id',
                'starts_at',
                'ends_at',
            ], 'reservation_items_resource_period_index');
            $table->index(['reservation_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_items');
    }
};
PHPFILE

mkdir -p database/migrations
cat > database/migrations/2026_06_27_000700_create_reservation_participants_table.php <<'PHPFILE'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 150);
            $table->string('email')->nullable();
            $table->string('role', 30)->default('guest');
            $table->string('status', 30)->default('registered');
            $table->timestampTz('checked_in_at')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['reservation_id', 'status']);
            $table->index(['customer_id', 'status']);
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_participants');
    }
};
PHPFILE

mkdir -p database/migrations
cat > database/migrations/2026_06_27_000800_create_reservation_status_histories_table.php <<'PHPFILE'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->text('reason')->nullable();
            $table->foreignId('changed_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestampTz('effective_at');
            $table->jsonb('metadata')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->index([
                'reservation_id',
                'effective_at',
            ], 'reservation_histories_effective_index');
            $table->index('to_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_status_histories');
    }
};
PHPFILE

mkdir -p database/migrations
cat > database/migrations/2026_06_27_000900_add_phase_two_postgresql_constraints.php <<'PHPFILE'
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
PHPFILE

mkdir -p app/Models
cat > app/Models/Venue.php <<'PHPFILE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'organization_id',
    'name',
    'slug',
    'status',
    'timezone',
    'address_line_1',
    'city',
    'country_code',
    'settings',
])]
class Venue extends Model
{
    use HasFactory, SoftDeletes;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }
}
PHPFILE

mkdir -p app/Models
cat > app/Models/Facility.php <<'PHPFILE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'venue_id',
    'code',
    'name',
    'type',
    'status',
    'settings',
])]
class Facility extends Model
{
    use HasFactory, SoftDeletes;

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }
}
PHPFILE

mkdir -p app/Models
cat > app/Models/Resource.php <<'PHPFILE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'facility_id',
    'code',
    'name',
    'type',
    'status',
    'capacity',
    'is_bookable',
    'settings',
])]
class Resource extends Model
{
    use HasFactory, SoftDeletes;

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function availabilityBlocks(): HasMany
    {
        return $this->hasMany(ResourceAvailabilityBlock::class);
    }

    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(
            Reservation::class,
            'reservation_items',
        )
            ->withPivot([
                'id',
                'starts_at',
                'ends_at',
                'quantity',
                'unit_price',
                'total_price',
                'status',
            ])
            ->withTimestamps();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_bookable' => 'boolean',
            'settings' => 'array',
        ];
    }
}
PHPFILE

mkdir -p app/Models
cat > app/Models/ResourceAvailabilityBlock.php <<'PHPFILE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'resource_id',
    'type',
    'starts_at',
    'ends_at',
    'reason',
    'created_by_user_id',
    'metadata',
])]
class ResourceAvailabilityBlock extends Model
{
    use HasFactory, SoftDeletes;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
PHPFILE

mkdir -p app/Models
cat > app/Models/Reservation.php <<'PHPFILE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'organization_id',
    'venue_id',
    'customer_id',
    'created_by_user_id',
    'reference_number',
    'status',
    'starts_at',
    'ends_at',
    'party_size',
    'subtotal',
    'discount_total',
    'total',
    'currency',
    'notes',
    'cancelled_at',
    'cancellation_reason',
])]
class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(ReservationParticipant::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ReservationStatusHistory::class);
    }

    public function latestStatusHistory(): HasOne
    {
        return $this->hasOne(ReservationStatusHistory::class)
            ->latestOfMany();
    }

    public function effectiveStatusHistory(): HasOne
    {
        return $this->hasOne(ReservationStatusHistory::class)
            ->ofMany([
                'effective_at' => 'max',
                'id' => 'max',
            ]);
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(
            Resource::class,
            'reservation_items',
        )
            ->withPivot([
                'id',
                'starts_at',
                'ends_at',
                'quantity',
                'unit_price',
                'total_price',
                'status',
            ])
            ->withTimestamps();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'total' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }
}
PHPFILE

mkdir -p app/Models
cat > app/Models/ReservationItem.php <<'PHPFILE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'reservation_id',
    'resource_id',
    'starts_at',
    'ends_at',
    'quantity',
    'unit_price',
    'total_price',
    'status',
    'notes',
])]
class ReservationItem extends Model
{
    use HasFactory, SoftDeletes;

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }
}
PHPFILE

mkdir -p app/Models
cat > app/Models/ReservationParticipant.php <<'PHPFILE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'reservation_id',
    'customer_id',
    'first_name',
    'last_name',
    'email',
    'role',
    'status',
    'checked_in_at',
])]
class ReservationParticipant extends Model
{
    use HasFactory, SoftDeletes;

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
        ];
    }
}
PHPFILE

mkdir -p app/Models
cat > app/Models/ReservationStatusHistory.php <<'PHPFILE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'reservation_id',
    'from_status',
    'to_status',
    'reason',
    'changed_by_user_id',
    'effective_at',
    'metadata',
])]
class ReservationStatusHistory extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'effective_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
PHPFILE

mkdir -p database/factories
cat > database/factories/VenueFactory.php <<'PHPFILE'
<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Venue>
 */
class VenueFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company().' Venue';

        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(
                1000,
                999999,
            ),
            'status' => 'active',
            'timezone' => 'Europe/Amsterdam',
            'address_line_1' => fake()->streetAddress(),
            'city' => fake()->city(),
            'country_code' => 'NL',
            'settings' => [
                'allow_online_booking' => true,
            ],
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }
}
PHPFILE

mkdir -p database/factories
cat > database/factories/FacilityFactory.php <<'PHPFILE'
<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'uuid' => Str::uuid(),
            'venue_id' => Venue::factory(),
            'code' => strtoupper(fake()->unique()->bothify('FAC-####')),
            'name' => ucfirst($name),
            'type' => fake()->randomElement([
                'course',
                'court',
                'hospitality',
                'wellness',
                'meeting',
            ]),
            'status' => 'active',
            'settings' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }
}
PHPFILE

mkdir -p database/factories
cat > database/factories/ResourceFactory.php <<'PHPFILE'
<?php

namespace Database\Factories;

use App\Models\Facility;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Resource>
 */
class ResourceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'facility_id' => Facility::factory(),
            'code' => strtoupper(fake()->unique()->bothify('RES-####')),
            'name' => ucfirst(fake()->unique()->words(2, true)),
            'type' => fake()->randomElement([
                'course',
                'court',
                'room',
                'table',
                'simulator',
            ]),
            'status' => 'active',
            'capacity' => fake()->numberBetween(1, 12),
            'is_bookable' => true,
            'settings' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'is_bookable' => true,
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
            'is_bookable' => false,
        ]);
    }

    public function withCapacity(int $capacity): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => $capacity,
        ]);
    }
}
PHPFILE

mkdir -p database/factories
cat > database/factories/ResourceAvailabilityBlockFactory.php <<'PHPFILE'
<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\ResourceAvailabilityBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceAvailabilityBlock>
 */
class ResourceAvailabilityBlockFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = now()->addDays(fake()->numberBetween(1, 30));

        return [
            'resource_id' => Resource::factory(),
            'type' => 'unavailable',
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHour(),
            'reason' => fake()->optional()->sentence(),
            'created_by_user_id' => null,
            'metadata' => null,
        ];
    }

    public function forResource(Resource $resource): static
    {
        return $this->state(fn (array $attributes) => [
            'resource_id' => $resource->id,
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'maintenance',
        ]);
    }
}
PHPFILE

mkdir -p database/factories
cat > database/factories/ReservationFactory.php <<'PHPFILE'
<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\Reservation;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = now()
            ->addDays(fake()->numberBetween(1, 30))
            ->setMinute(0)
            ->setSecond(0);

        $subtotal = fake()->randomFloat(2, 25, 250);

        return [
            'uuid' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'venue_id' => Venue::factory(),
            'customer_id' => Customer::factory(),
            'created_by_user_id' => null,
            'reference_number' => strtoupper(
                fake()->unique()->bothify('RES-########'),
            ),
            'status' => 'pending',
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHour(),
            'party_size' => fake()->numberBetween(1, 8),
            'subtotal' => $subtotal,
            'discount_total' => 0,
            'total' => $subtotal,
            'currency' => 'EUR',
            'notes' => null,
            'cancelled_at' => null,
            'cancellation_reason' => null,
        ];
    }

    public function forCustomerAtVenue(
        Customer $customer,
        Venue $venue,
    ): static {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $customer->organization_id,
            'venue_id' => $venue->id,
            'customer_id' => $customer->id,
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => now()->subHour(),
            'cancellation_reason' => fake()->sentence(),
        ]);
    }

    public function noShow(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'no_show',
        ]);
    }
}
PHPFILE

mkdir -p database/factories
cat > database/factories/ReservationItemFactory.php <<'PHPFILE'
<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservationItem>
 */
class ReservationItemFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = now()->addDay()->setMinute(0)->setSecond(0);
        $unitPrice = fake()->randomFloat(2, 10, 100);

        return [
            'reservation_id' => Reservation::factory(),
            'resource_id' => Resource::factory(),
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHour(),
            'quantity' => 1,
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice,
            'status' => 'reserved',
            'notes' => null,
        ];
    }

    public function forReservationAndResource(
        Reservation $reservation,
        Resource $resource,
    ): static {
        return $this->state(fn (array $attributes) => [
            'reservation_id' => $reservation->id,
            'resource_id' => $resource->id,
            'starts_at' => $reservation->starts_at,
            'ends_at' => $reservation->ends_at,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
PHPFILE

mkdir -p database/factories
cat > database/factories/ReservationParticipantFactory.php <<'PHPFILE'
<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Reservation;
use App\Models\ReservationParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservationParticipant>
 */
class ReservationParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'customer_id' => null,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->optional()->safeEmail(),
            'role' => 'guest',
            'status' => 'registered',
            'checked_in_at' => null,
        ];
    }

    public function forReservation(Reservation $reservation): static
    {
        return $this->state(fn (array $attributes) => [
            'reservation_id' => $reservation->id,
        ]);
    }

    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
        ]);
    }

    public function booker(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'booker',
        ]);
    }

    public function attended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'attended',
            'checked_in_at' => now(),
        ]);
    }

    public function noShow(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'no_show',
            'checked_in_at' => null,
        ]);
    }
}
PHPFILE

mkdir -p database/factories
cat > database/factories/ReservationStatusHistoryFactory.php <<'PHPFILE'
<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\ReservationStatusHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservationStatusHistory>
 */
class ReservationStatusHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'from_status' => null,
            'to_status' => 'pending',
            'reason' => null,
            'changed_by_user_id' => null,
            'effective_at' => now(),
            'metadata' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'pending',
            'to_status' => 'confirmed',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'confirmed',
            'to_status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'confirmed',
            'to_status' => 'cancelled',
        ]);
    }

    public function noShow(): static
    {
        return $this->state(fn (array $attributes) => [
            'from_status' => 'confirmed',
            'to_status' => 'no_show',
        ]);
    }
}
PHPFILE

mkdir -p database/seeders
cat > database/seeders/PhaseTwoScenarioSeeder.php <<'PHPFILE'
<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Facility;
use App\Models\Organization;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationParticipant;
use App\Models\ReservationStatusHistory;
use App\Models\Resource;
use App\Models\ResourceAvailabilityBlock;
use App\Models\User;
use App\Models\Venue;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class PhaseTwoScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $now = CarbonImmutable::now()->startOfMinute();

        $group = Organization::query()
            ->where('slug', 'venueops-leisure-group')
            ->firstOrFail();

        $greenValley = Organization::query()
            ->where('slug', 'green-valley-golf-club')
            ->firstOrFail();

        $rotterdamPadel = Organization::query()
            ->where('slug', 'rotterdam-padel-centre')
            ->firstOrFail();

        $serenityWellness = Organization::query()
            ->where('slug', 'serenity-wellness')
            ->firstOrFail();

        $owner = User::query()
            ->where('email', 'owner@queryautism.test')
            ->firstOrFail();

        $multiManager = User::query()
            ->where('email', 'multi.manager@queryautism.test')
            ->firstOrFail();

        $pascalMember = $this->customer('GV-0001');
        $greenGuest = $this->customer('GV-0002');
        $blockedCustomer = $this->customer('GV-0003');
        $multiGreen = $this->customer('GV-0004');
        $multiRotterdam = $this->customer('RP-0001');
        $noMembershipCustomer = $this->customer('RP-0002');

        $serenityCustomer = Customer::factory()
            ->for($serenityWellness)
            ->guest()
            ->create([
                'customer_number' => 'SW-0001',
                'first_name' => 'Nina',
                'last_name' => 'No Show',
                'email' => 'nina.noshow@example.test',
            ]);

        $groupVenue = Venue::factory()
            ->for($group)
            ->active()
            ->create([
                'name' => 'VenueOps Training Centre',
                'slug' => 'venueops-training-centre',
                'city' => 'Rotterdam',
            ]);

        $greenVenue = Venue::factory()
            ->for($greenValley)
            ->active()
            ->create([
                'name' => 'Green Valley Main Venue',
                'slug' => 'green-valley-main-venue',
                'city' => 'Schiedam',
            ]);

        $rotterdamVenue = Venue::factory()
            ->for($rotterdamPadel)
            ->active()
            ->create([
                'name' => 'Rotterdam Padel Hall',
                'slug' => 'rotterdam-padel-hall',
                'city' => 'Rotterdam',
            ]);

        $serenityVenue = Venue::factory()
            ->for($serenityWellness)
            ->active()
            ->create([
                'name' => 'Serenity Spa',
                'slug' => 'serenity-spa',
                'city' => 'Delft',
            ]);

        $meetingRooms = $this->facility(
            $groupVenue,
            'MEETING',
            'Meeting Rooms',
            'meeting',
        );

        $golfCourse = $this->facility(
            $greenVenue,
            'COURSE',
            'Golf Course',
            'course',
        );

        $clubhouse = $this->facility(
            $greenVenue,
            'CLUB',
            'Clubhouse',
            'hospitality',
        );

        $padelCourts = $this->facility(
            $rotterdamVenue,
            'COURTS',
            'Padel Courts',
            'court',
        );

        $padelLounge = $this->facility(
            $rotterdamVenue,
            'LOUNGE',
            'Padel Lounge',
            'hospitality',
        );

        $treatmentRooms = $this->facility(
            $serenityVenue,
            'TREATMENT',
            'Treatment Rooms',
            'wellness',
        );

        $boardroom = $this->resource(
            $meetingRooms,
            'BOARDROOM',
            'Boardroom',
            'room',
            12,
        );

        $northCourse = $this->resource(
            $golfCourse,
            'NORTH',
            'North Course',
            'course',
            4,
        );

        $simulator = $this->resource(
            $clubhouse,
            'SIM-1',
            'Simulator 1',
            'simulator',
            4,
        );

        $tableA = $this->resource(
            $clubhouse,
            'TABLE-A',
            'Restaurant Table A',
            'table',
            6,
        );

        $courtOne = $this->resource(
            $padelCourts,
            'COURT-1',
            'Padel Court 1',
            'court',
            4,
        );

        $courtTwo = $this->resource(
            $padelCourts,
            'COURT-2',
            'Padel Court 2',
            'court',
            4,
        );

        $courtThree = Resource::factory()
            ->for($padelCourts)
            ->maintenance()
            ->withCapacity(4)
            ->create([
                'code' => 'COURT-3',
                'name' => 'Padel Court 3',
                'type' => 'court',
            ]);

        $loungeTable = $this->resource(
            $padelLounge,
            'LOUNGE-TABLE',
            'Lounge Table',
            'table',
            8,
        );

        $treatmentRoom = $this->resource(
            $treatmentRooms,
            'ROOM-1',
            'Treatment Room 1',
            'room',
            2,
        );

        ResourceAvailabilityBlock::factory()
            ->forResource($courtTwo)
            ->maintenance()
            ->create([
                'starts_at' => $now->addDay()->setTime(10, 0),
                'ends_at' => $now->addDay()->setTime(12, 0),
                'reason' => 'Scheduled court maintenance',
                'created_by_user_id' => $multiManager->id,
            ]);

        ResourceAvailabilityBlock::factory()
            ->forResource($northCourse)
            ->create([
                'starts_at' => $now->addDays(3)->setTime(15, 0),
                'ends_at' => $now->addDays(3)->setTime(17, 0),
                'reason' => 'Private tournament preparation',
                'created_by_user_id' => $owner->id,
            ]);

        $overlapOne = $this->reservation(
            $pascalMember,
            $greenVenue,
            'GV-RES-0001',
            'confirmed',
            $now->addDay()->setTime(9, 0),
            $now->addDay()->setTime(10, 0),
            4,
            120,
            $owner,
        );
        $this->item($overlapOne, $northCourse, 120);
        $this->participants($overlapOne, $pascalMember, 4);
        $this->pendingAndConfirmedHistory($overlapOne, $owner, $now);

        $overlapTwo = $this->reservation(
            $greenGuest,
            $greenVenue,
            'GV-RES-0002',
            'confirmed',
            $now->addDay()->setTime(9, 30),
            $now->addDay()->setTime(10, 30),
            2,
            60,
            $owner,
        );
        $this->item($overlapTwo, $northCourse, 60);
        $this->participants($overlapTwo, $greenGuest, 2);
        $this->history(
            $overlapTwo,
            null,
            'confirmed',
            $now->subHours(8),
            $owner,
        );

        $completed = $this->reservation(
            $multiRotterdam,
            $rotterdamVenue,
            'RP-RES-0001',
            'completed',
            $now->subDay()->setTime(18, 0),
            $now->subDay()->setTime(19, 0),
            4,
            80,
            $multiManager,
        );
        $this->item($completed, $courtOne, 80, 'completed');
        $this->participants(
            $completed,
            $multiRotterdam,
            4,
            'attended',
        );
        $this->history(
            $completed,
            null,
            'confirmed',
            $now->subDays(2),
            $multiManager,
        );
        $this->history(
            $completed,
            'confirmed',
            'completed',
            $now->subDay()->setTime(19, 0),
            $multiManager,
        );

        $cancelled = $this->reservation(
            $blockedCustomer,
            $greenVenue,
            'GV-RES-0003',
            'cancelled',
            $now->addDay()->setTime(19, 0),
            $now->addDay()->setTime(20, 0),
            6,
            150,
            $owner,
            [
                'cancelled_at' => $now->subHour(),
                'cancellation_reason' => 'Customer requested cancellation',
            ],
        );
        $this->item($cancelled, $tableA, 150, 'cancelled');
        $this->participants($cancelled, $blockedCustomer, 1, 'cancelled');
        $this->history(
            $cancelled,
            null,
            'confirmed',
            $now->subDay(),
            $owner,
        );
        $this->history(
            $cancelled,
            'confirmed',
            'cancelled',
            $now->subHour(),
            $owner,
        );

        $blockedByMaintenance = $this->reservation(
            $noMembershipCustomer,
            $rotterdamVenue,
            'RP-RES-0002',
            'confirmed',
            $now->addDay()->setTime(10, 30),
            $now->addDay()->setTime(11, 30),
            4,
            80,
            $multiManager,
        );
        $this->item($blockedByMaintenance, $courtTwo, 80);
        $this->participants(
            $blockedByMaintenance,
            $noMembershipCustomer,
            4,
        );
        $this->history(
            $blockedByMaintenance,
            null,
            'confirmed',
            $now->subHours(4),
            $multiManager,
        );

        $noShow = $this->reservation(
            $serenityCustomer,
            $serenityVenue,
            'SW-RES-0001',
            'no_show',
            $now->subDay()->setTime(14, 0),
            $now->subDay()->setTime(15, 0),
            1,
            90,
            null,
        );
        $this->item($noShow, $treatmentRoom, 90, 'completed');
        $this->participants($noShow, $serenityCustomer, 1, 'no_show');
        $this->history(
            $noShow,
            null,
            'confirmed',
            $now->subDays(2),
        );
        $this->history(
            $noShow,
            'confirmed',
            'no_show',
            $now->subDay()->setTime(15, 0),
        );

        $overCapacity = $this->reservation(
            $multiGreen,
            $greenVenue,
            'GV-RES-0004',
            'confirmed',
            $now->addDays(2)->setTime(11, 0),
            $now->addDays(2)->setTime(12, 0),
            5,
            100,
            $owner,
        );
        $this->item($overCapacity, $simulator, 100);
        $this->participants($overCapacity, $multiGreen, 5);
        $this->history(
            $overCapacity,
            null,
            'confirmed',
            $now->subHours(2),
            $owner,
        );

        $statusMismatch = $this->reservation(
            $multiRotterdam,
            $rotterdamVenue,
            'RP-RES-0003',
            'pending',
            $now->addDays(4)->setTime(20, 0),
            $now->addDays(4)->setTime(21, 0),
            3,
            0,
            $multiManager,
        );
        $this->item($statusMismatch, $loungeTable, 0);
        $this->history(
            $statusMismatch,
            null,
            'pending',
            $now->subDays(2),
            $multiManager,
        );
        $this->history(
            $statusMismatch,
            'pending',
            'confirmed',
            $now->subHour(),
            $multiManager,
        );

        unset(
            $boardroom,
            $courtThree,
        );
    }

    private function customer(string $customerNumber): Customer
    {
        return Customer::query()
            ->where('customer_number', $customerNumber)
            ->firstOrFail();
    }

    private function facility(
        Venue $venue,
        string $code,
        string $name,
        string $type,
    ): Facility {
        return Facility::factory()
            ->for($venue)
            ->active()
            ->create([
                'code' => $code,
                'name' => $name,
                'type' => $type,
            ]);
    }

    private function resource(
        Facility $facility,
        string $code,
        string $name,
        string $type,
        int $capacity,
    ): Resource {
        return Resource::factory()
            ->for($facility)
            ->active()
            ->withCapacity($capacity)
            ->create([
                'code' => $code,
                'name' => $name,
                'type' => $type,
            ]);
    }

    /**
     * @param array<string, mixed> $extra
     */
    private function reservation(
        Customer $customer,
        Venue $venue,
        string $referenceNumber,
        string $status,
        CarbonImmutable $startsAt,
        CarbonImmutable $endsAt,
        int $partySize,
        float|int $total,
        ?User $createdBy,
        array $extra = [],
    ): Reservation {
        return Reservation::factory()
            ->forCustomerAtVenue($customer, $venue)
            ->create(array_merge([
                'reference_number' => $referenceNumber,
                'status' => $status,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'party_size' => $partySize,
                'subtotal' => $total,
                'discount_total' => 0,
                'total' => $total,
                'created_by_user_id' => $createdBy?->id,
            ], $extra));
    }

    private function item(
        Reservation $reservation,
        Resource $resource,
        float|int $price,
        string $status = 'reserved',
    ): ReservationItem {
        return ReservationItem::factory()
            ->forReservationAndResource($reservation, $resource)
            ->create([
                'unit_price' => $price,
                'total_price' => $price,
                'status' => $status,
            ]);
    }

    private function participants(
        Reservation $reservation,
        Customer $booker,
        int $amount,
        string $status = 'registered',
    ): void {
        ReservationParticipant::factory()
            ->forReservation($reservation)
            ->forCustomer($booker)
            ->booker()
            ->create([
                'status' => $status,
                'checked_in_at' => $status === 'attended'
                    ? $reservation->starts_at
                    : null,
            ]);

        if ($amount <= 1) {
            return;
        }

        ReservationParticipant::factory()
            ->count($amount - 1)
            ->forReservation($reservation)
            ->create([
                'status' => $status,
                'checked_in_at' => $status === 'attended'
                    ? $reservation->starts_at
                    : null,
            ]);
    }

    private function pendingAndConfirmedHistory(
        Reservation $reservation,
        User $changedBy,
        CarbonImmutable $now,
    ): void {
        $this->history(
            $reservation,
            null,
            'pending',
            $now->subDay(),
            $changedBy,
        );

        $this->history(
            $reservation,
            'pending',
            'confirmed',
            $now->subHours(12),
            $changedBy,
        );
    }

    private function history(
        Reservation $reservation,
        ?string $fromStatus,
        string $toStatus,
        CarbonImmutable $effectiveAt,
        ?User $changedBy = null,
    ): ReservationStatusHistory {
        return ReservationStatusHistory::factory()
            ->for($reservation)
            ->create([
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'changed_by_user_id' => $changedBy?->id,
                'effective_at' => $effectiveAt,
            ]);
    }
}
PHPFILE

mkdir -p tests/Feature/Data
cat > tests/Feature/Data/PhaseTwoFactoriesTest.php <<'PHPFILE'
<?php

namespace Tests\Feature\Data;

use App\Models\Customer;
use App\Models\Facility;
use App\Models\Organization;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationParticipant;
use App\Models\ReservationStatusHistory;
use App\Models\Resource;
use App\Models\ResourceAvailabilityBlock;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseTwoFactoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_two_factories_and_states_create_valid_data(): void
    {
        $organization = Organization::factory()->active()->create();
        $customer = Customer::factory()->for($organization)->create();
        $venue = Venue::factory()->for($organization)->active()->create();
        $facility = Facility::factory()->for($venue)->active()->create();
        $resource = Resource::factory()
            ->for($facility)
            ->active()
            ->withCapacity(4)
            ->create();

        $reservation = Reservation::factory()
            ->forCustomerAtVenue($customer, $venue)
            ->confirmed()
            ->create();

        $item = ReservationItem::factory()
            ->forReservationAndResource($reservation, $resource)
            ->create();

        $participant = ReservationParticipant::factory()
            ->forReservation($reservation)
            ->forCustomer($customer)
            ->booker()
            ->create();

        $block = ResourceAvailabilityBlock::factory()
            ->forResource($resource)
            ->maintenance()
            ->create();

        $history = ReservationStatusHistory::factory()
            ->for($reservation)
            ->confirmed()
            ->create();

        $this->assertSame('active', $venue->status);
        $this->assertTrue($facility->venue->is($venue));
        $this->assertSame(4, $resource->capacity);
        $this->assertTrue($resource->is_bookable);
        $this->assertSame('confirmed', $reservation->status);
        $this->assertTrue($reservation->customer->is($customer));
        $this->assertTrue($item->resource->is($resource));
        $this->assertSame('booker', $participant->role);
        $this->assertSame('maintenance', $block->type);
        $this->assertSame('confirmed', $history->to_status);
    }
}
PHPFILE

mkdir -p tests/Feature/Data
cat > tests/Feature/Data/PhaseTwoRelationshipsTest.php <<'PHPFILE'
<?php

namespace Tests\Feature\Data;

use App\Models\Customer;
use App\Models\Facility;
use App\Models\Organization;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationParticipant;
use App\Models\ReservationStatusHistory;
use App\Models\Resource;
use App\Models\ResourceAvailabilityBlock;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseTwoRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_two_relationships_are_configured(): void
    {
        $organization = Organization::factory()->active()->create();
        $customer = Customer::factory()->for($organization)->create();
        $venue = Venue::factory()->for($organization)->create();
        $facility = Facility::factory()->for($venue)->create();
        $resource = Resource::factory()->for($facility)->create();

        $reservation = Reservation::factory()
            ->forCustomerAtVenue($customer, $venue)
            ->create();

        $item = ReservationItem::factory()
            ->forReservationAndResource($reservation, $resource)
            ->create();

        $participant = ReservationParticipant::factory()
            ->forReservation($reservation)
            ->forCustomer($customer)
            ->create();

        $block = ResourceAvailabilityBlock::factory()
            ->forResource($resource)
            ->create();

        $history = ReservationStatusHistory::factory()
            ->for($reservation)
            ->create();

        $this->assertTrue($organization->venues->contains($venue));
        $this->assertTrue($organization->reservations->contains($reservation));
        $this->assertTrue($venue->organization->is($organization));
        $this->assertTrue($venue->facilities->contains($facility));
        $this->assertTrue($venue->reservations->contains($reservation));
        $this->assertTrue($facility->venue->is($venue));
        $this->assertTrue($facility->resources->contains($resource));
        $this->assertTrue($resource->facility->is($facility));
        $this->assertTrue($resource->reservationItems->contains($item));
        $this->assertTrue($resource->availabilityBlocks->contains($block));
        $this->assertTrue($resource->reservations->contains($reservation));
        $this->assertTrue($customer->reservations->contains($reservation));
        $this->assertTrue(
            $customer->reservationParticipants->contains($participant),
        );
        $this->assertTrue($reservation->items->contains($item));
        $this->assertTrue(
            $reservation->participants->contains($participant),
        );
        $this->assertTrue(
            $reservation->statusHistories->contains($history),
        );
        $this->assertTrue($reservation->resources->contains($resource));
        $this->assertTrue($item->reservation->is($reservation));
        $this->assertTrue($participant->customer->is($customer));
        $this->assertTrue($block->resource->is($resource));
        $this->assertTrue($history->reservation->is($reservation));
    }
}
PHPFILE

mkdir -p tests/Feature/Data
cat > tests/Feature/Data/PhaseTwoScenarioSeederTest.php <<'PHPFILE'
<?php

namespace Tests\Feature\Data;

use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Resource;
use App\Models\ResourceAvailabilityBlock;
use Database\Seeders\PhaseOneScenarioSeeder;
use Database\Seeders\PhaseTwoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseTwoScenarioSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PhaseOneScenarioSeeder::class,
            PhaseTwoScenarioSeeder::class,
        ]);
    }

    public function test_seeder_creates_the_expected_phase_two_dataset(): void
    {
        $this->assertDatabaseCount('venues', 4);
        $this->assertDatabaseCount('facilities', 6);
        $this->assertDatabaseCount('resources', 9);
        $this->assertDatabaseCount('resource_availability_blocks', 2);
        $this->assertDatabaseCount('reservations', 8);
        $this->assertDatabaseCount('reservation_items', 8);
        $this->assertDatabaseCount('reservation_participants', 21);
        $this->assertDatabaseCount('reservation_status_histories', 13);
    }

    public function test_seeder_contains_required_reservation_scenarios(): void
    {
        $overlapItems = ReservationItem::query()
            ->whereHas('resource', function ($query) {
                $query->where('code', 'NORTH');
            })
            ->orderBy('starts_at')
            ->get();

        $this->assertCount(2, $overlapItems);
        $this->assertTrue(
            $overlapItems[0]->ends_at->gt($overlapItems[1]->starts_at),
        );

        $maintenanceConflict = ReservationItem::query()
            ->whereHas('resource', function ($query) {
                $query->where('code', 'COURT-2');
            })
            ->firstOrFail();

        $this->assertTrue(
            ResourceAvailabilityBlock::query()
                ->where('resource_id', $maintenanceConflict->resource_id)
                ->where('starts_at', '<', $maintenanceConflict->ends_at)
                ->where('ends_at', '>', $maintenanceConflict->starts_at)
                ->exists(),
        );

        $overCapacity = Reservation::query()
            ->where('reference_number', 'GV-RES-0004')
            ->with('items.resource')
            ->firstOrFail();

        $this->assertGreaterThan(
            $overCapacity->items->first()->resource->capacity,
            $overCapacity->party_size,
        );

        $this->assertTrue(
            Reservation::query()
                ->where('reference_number', 'RP-RES-0003')
                ->doesntHave('participants')
                ->exists(),
        );

        $statusMismatch = Reservation::query()
            ->where('reference_number', 'RP-RES-0003')
            ->with('effectiveStatusHistory')
            ->firstOrFail();

        $this->assertSame('pending', $statusMismatch->status);
        $this->assertSame(
            'confirmed',
            $statusMismatch->effectiveStatusHistory->to_status,
        );

        $this->assertSame(
            2,
            Resource::query()
                ->whereDoesntHave('reservationItems')
                ->count(),
        );
    }
}
PHPFILE

mkdir -p docs/database
cat > docs/database/queryautism-phase-2-reservations-resources.md <<'PHPFILE'
# QueryAutism — Phase 2 Blueprint

## Venues, facilities, resources and reservations

Phase Two adds the operational booking layer on top of the identity and
membership data from Phase One.

## Tables

1. `venues`
2. `facilities`
3. `resources`
4. `resource_availability_blocks`
5. `reservations`
6. `reservation_items`
7. `reservation_participants`
8. `reservation_status_histories`

## Relationship map

```text
Organization
├── hasMany Venues
└── hasMany Reservations

Venue
├── belongsTo Organization
├── hasMany Facilities
└── hasMany Reservations

Facility
├── belongsTo Venue
└── hasMany Resources

Resource
├── belongsTo Facility
├── hasMany ResourceAvailabilityBlocks
├── hasMany ReservationItems
└── belongsToMany Reservations through ReservationItems

Customer
├── hasMany Reservations
└── hasMany ReservationParticipants

Reservation
├── belongsTo Organization
├── belongsTo Venue
├── belongsTo Customer
├── hasMany ReservationItems
├── belongsToMany Resources through ReservationItems
├── hasMany ReservationParticipants
├── hasMany ReservationStatusHistories
├── hasOne latestStatusHistory
└── hasOne effectiveStatusHistory
```

## Deterministic query scenarios

The Phase Two scenario seeder includes:

- overlapping bookings for the same resource;
- a reservation during a maintenance block;
- a reservation whose party size exceeds resource capacity;
- completed, cancelled and no-show reservations;
- a reservation without participants;
- a stored reservation status that differs from effective history;
- resources that have never been booked;
- venues belonging to different organizations.

## Planned query coverage

QRY-051 through QRY-075 will cover:

- nested eager loading;
- resources without bookings;
- upcoming and historical reservations;
- time-overlap detection;
- maintenance conflicts;
- capacity violations;
- participant counts and attendance;
- revenue and occupancy reports;
- status-history consistency;
- multi-tenant reporting;
- joins, subqueries, aggregates and PostgreSQL date logic.
PHPFILE

echo "Phase Two foundation created."
echo
echo "Run:"
echo "  php artisan migrate:fresh --seed"
echo "  php artisan test tests/Feature/Data"
echo "  php artisan test"
echo "  git diff --check"
echo "  git status --short"
