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
