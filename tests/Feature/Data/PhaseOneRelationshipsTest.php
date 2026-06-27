<?php

namespace Tests\Feature\Data;

use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\ExternalIdentifier;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\MembershipStatusHistory;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PhaseOneRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_one_relationships_are_configured(): void
    {
        $user = User::query()->create([
            'uuid' => Str::uuid(),
            'name' => 'Pascal Example',
            'email' => 'pascal@example.test',
            'email_verified_at' => now(),
            'password' => 'password',
            'status' => 'active',
            'locale' => 'en',
            'timezone' => 'Europe/Amsterdam',
        ]);

        $profile = UserProfile::query()->create([
            'user_id' => $user->id,
            'first_name' => 'Pascal',
            'last_name' => 'Example',
            'preferred_contact_method' => 'email',
            'marketing_consent' => true,
        ]);

        $parentOrganization = Organization::query()->create([
            'uuid' => Str::uuid(),
            'name' => 'Leisure Group',
            'slug' => 'leisure-group',
            'status' => 'active',
            'timezone' => 'Europe/Amsterdam',
            'currency' => 'EUR',
            'country_code' => 'NL',
        ]);

        $organization = Organization::query()->create([
            'uuid' => Str::uuid(),
            'parent_id' => $parentOrganization->id,
            'name' => 'Rotterdam Venue',
            'slug' => 'rotterdam-venue',
            'status' => 'active',
            'timezone' => 'Europe/Amsterdam',
            'currency' => 'EUR',
            'country_code' => 'NL',
        ]);

        OrganizationUser::query()->create([
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'role' => 'manager',
            'status' => 'active',
            'is_owner' => false,
            'joined_at' => now(),
        ]);

        $customer = Customer::query()->create([
            'uuid' => Str::uuid(),
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'customer_number' => 'CUS-000001',
            'first_name' => 'Pascal',
            'last_name' => 'Example',
            'email' => 'pascal@example.test',
            'status' => 'active',
            'source' => 'online',
            'marketing_consent' => true,
            'registered_at' => now(),
        ]);

        $customerProfile = CustomerProfile::query()->create([
            'customer_id' => $customer->id,
            'preferred_language' => 'en',
            'preferred_timezone' => 'Europe/Amsterdam',
            'preferred_currency' => 'EUR',
            'average_booking_value' => 50,
            'total_reservations' => 5,
            'total_spent' => 250,
            'no_show_count' => 0,
            'cancellation_count' => 1,
            'loyalty_tier' => 'silver',
            'risk_score' => 10,
            'last_recalculated_at' => now(),
        ]);

        $plan = MembershipPlan::query()->create([
            'uuid' => Str::uuid(),
            'organization_id' => $organization->id,
            'code' => 'PREMIUM',
            'name' => 'Premium Member',
            'status' => 'active',
            'billing_interval' => 'yearly',
            'price' => 500,
            'currency' => 'EUR',
            'booking_window_days' => 30,
            'priority' => 100,
        ]);

        $membership = Membership::query()->create([
            'uuid' => Str::uuid(),
            'organization_id' => $organization->id,
            'customer_id' => $customer->id,
            'membership_plan_id' => $plan->id,
            'membership_number' => 'MEM-000001',
            'status' => 'active',
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->addYear(),
            'activated_at' => now()->subMonth(),
            'auto_renew' => true,
            'agreed_price' => 450,
            'currency' => 'EUR',
        ]);

        $olderHistory = MembershipStatusHistory::query()->create([
            'membership_id' => $membership->id,
            'from_status' => null,
            'to_status' => 'pending',
            'effective_at' => now()->subMonth()->subDay(),
        ]);

        $effectiveHistory = MembershipStatusHistory::query()->create([
            'membership_id' => $membership->id,
            'from_status' => 'pending',
            'to_status' => 'active',
            'changed_by_user_id' => $user->id,
            'effective_at' => now()->subMonth(),
        ]);

        $identifier = ExternalIdentifier::query()->create([
            'organization_id' => $organization->id,
            'customer_id' => $customer->id,
            'provider' => 'golf_federation',
            'identifier_type' => 'membership_number',
            'identifier_value' => ' NL-12345 ',
            'normalized_value' => 'NL-12345',
            'verified_at' => now(),
            'verification_source' => 'api',
        ]);

        $this->assertTrue($user->profile->is($profile));
        $this->assertTrue($user->organizations->contains($organization));
        $this->assertTrue($organization->users->contains($user));
        $this->assertTrue($organization->parent->is($parentOrganization));
        $this->assertTrue(
            $parentOrganization->children->contains($organization),
        );
        $this->assertTrue($customer->organization->is($organization));
        $this->assertTrue($customer->user->is($user));
        $this->assertTrue($customer->profile->is($customerProfile));
        $this->assertTrue($user->customerProfiles->contains($customerProfile));
        $this->assertTrue($customer->memberships->contains($membership));
        $this->assertTrue($membership->membershipPlan->is($plan));
        $this->assertTrue(
            $membership->statusHistories->contains($olderHistory),
        );
        $this->assertTrue(
            $membership->latestStatusHistory->is($effectiveHistory),
        );
        $this->assertTrue(
            $membership->effectiveStatusHistory->is($effectiveHistory),
        );
        $this->assertTrue(
            $customer->externalIdentifiers->contains($identifier),
        );
    }
}
