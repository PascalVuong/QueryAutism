<?php

namespace Tests\Feature\Data;

use App\Models\Customer;
use App\Models\ExternalIdentifier;
use App\Models\Membership;
use App\Models\Organization;
use App\Models\User;
use Database\Seeders\PhaseOneScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseOneScenarioSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_the_expected_phase_one_dataset(): void
    {
        $this->seed(PhaseOneScenarioSeeder::class);

        $this->assertDatabaseCount('organizations', 5);
        $this->assertDatabaseCount('users', 5);
        $this->assertDatabaseCount('user_profiles', 3);
        $this->assertDatabaseCount('organization_users', 7);
        $this->assertDatabaseCount('customers', 11);
        $this->assertDatabaseCount('customer_profiles', 6);
        $this->assertDatabaseCount('membership_plans', 7);
        $this->assertDatabaseCount('memberships', 8);
        $this->assertDatabaseCount(
            'membership_status_histories',
            10,
        );
        $this->assertDatabaseCount('external_identifiers', 5);
    }

    public function test_seeder_contains_required_query_scenarios(): void
    {
        $this->seed(PhaseOneScenarioSeeder::class);

        $multiManager = User::query()
            ->where('email', 'multi.manager@queryautism.test')
            ->firstOrFail();

        $this->assertCount(2, $multiManager->organizations);

        $this->assertTrue(
            User::query()
                ->where('email', 'no.profile@queryautism.test')
                ->doesntHave('profile')
                ->exists(),
        );

        $this->assertTrue(
            User::query()
                ->where('email', 'never.logged.in@queryautism.test')
                ->whereNull('last_login_at')
                ->exists(),
        );

        $this->assertTrue(
            Organization::query()
                ->where('slug', 'dormant-event-hall')
                ->whereDoesntHave('users', function ($query) {
                    $query->where(
                        'organization_users.status',
                        'active',
                    );
                })
                ->exists(),
        );

        $this->assertTrue(
            Customer::query()
                ->where('customer_number', 'GV-0002')
                ->whereNull('user_id')
                ->exists(),
        );

        $this->assertTrue(
            Customer::query()
                ->where('customer_number', 'RP-0001')
                ->whereHas('user', function ($query) {
                    $query->whereColumn(
                        'users.email',
                        '<>',
                        'customers.email',
                    );
                })
                ->exists(),
        );

        $overlapMemberships = Membership::query()
            ->whereHas('customer', function ($query) {
                $query->where('customer_number', 'GV-0005');
            })
            ->orderBy('starts_at')
            ->get();

        $this->assertCount(2, $overlapMemberships);
        $this->assertTrue(
            $overlapMemberships[0]->ends_at->gt(
                $overlapMemberships[1]->starts_at,
            ),
        );

        $statusMismatch = Membership::query()
            ->where('membership_number', 'RP-MEM-0002')
            ->with('effectiveStatusHistory')
            ->firstOrFail();

        $this->assertSame('active', $statusMismatch->status);
        $this->assertSame(
            'paused',
            $statusMismatch->effectiveStatusHistory->to_status,
        );

        $duplicateUnverifiedCount = ExternalIdentifier::query()
            ->where('provider', 'booking_partner')
            ->where('identifier_type', 'customer_number')
            ->where('normalized_value', 'DUP-2000')
            ->whereNull('verified_at')
            ->count();

        $this->assertSame(2, $duplicateUnverifiedCount);

        $crossOrganizationVerifiedCount = ExternalIdentifier::query()
            ->where('provider', 'golf_federation')
            ->where('identifier_type', 'membership_number')
            ->where('normalized_value', 'GVF-1001')
            ->whereNotNull('verified_at')
            ->distinct('organization_id')
            ->count('organization_id');

        $this->assertSame(2, $crossOrganizationVerifiedCount);
    }
}
