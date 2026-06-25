<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\ExternalIdentifier;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\MembershipStatusHistory;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class PhaseOneScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $now = CarbonImmutable::now();

        /*
         * Organizations
         */
        $group = Organization::factory()
            ->active()
            ->create([
                'name' => 'VenueOps Leisure Group',
                'legal_name' => 'VenueOps Leisure Group B.V.',
                'slug' => 'venueops-leisure-group',
                'email' => 'info@venueops.test',
            ]);

        $greenValley = Organization::factory()
            ->active()
            ->childOf($group)
            ->create([
                'name' => 'Green Valley Golf Club',
                'legal_name' => 'Green Valley Golf Club B.V.',
                'slug' => 'green-valley-golf-club',
                'email' => 'info@greenvalley.test',
            ]);

        $rotterdamPadel = Organization::factory()
            ->active()
            ->childOf($group)
            ->create([
                'name' => 'Rotterdam Padel Centre',
                'legal_name' => 'Rotterdam Padel Centre B.V.',
                'slug' => 'rotterdam-padel-centre',
                'email' => 'info@rotterdampadel.test',
            ]);

        $serenityWellness = Organization::factory()->create([
            'name' => 'Serenity Wellness',
            'legal_name' => 'Serenity Wellness B.V.',
            'slug' => 'serenity-wellness',
            'email' => 'info@serenity.test',
            'status' => 'trial',
        ]);

        Organization::factory()
            ->suspended()
            ->create([
                'name' => 'Dormant Event Hall',
                'legal_name' => 'Dormant Event Hall B.V.',
                'slug' => 'dormant-event-hall',
                'email' => 'info@dormantevents.test',
            ]);

        /*
         * Users
         */
        $owner = User::factory()
            ->verified()
            ->create([
                'name' => 'Olivia Owner',
                'email' => 'owner@queryautism.test',
                'status' => 'active',
            ]);

        UserProfile::factory()
            ->for($owner)
            ->marketingOptIn()
            ->create([
                'first_name' => 'Olivia',
                'last_name' => 'Owner',
            ]);

        $multiManager = User::factory()
            ->verified()
            ->create([
                'name' => 'Mila Manager',
                'email' => 'multi.manager@queryautism.test',
                'status' => 'active',
            ]);

        UserProfile::factory()
            ->for($multiManager)
            ->create([
                'first_name' => 'Mila',
                'last_name' => 'Manager',
            ]);

        $userWithoutProfile = User::factory()
            ->verified()
            ->create([
                'name' => 'No Profile User',
                'email' => 'no.profile@queryautism.test',
                'status' => 'active',
            ]);

        $suspendedUser = User::factory()
            ->suspended()
            ->unverified()
            ->create([
                'name' => 'Suspended Staff',
                'email' => 'suspended@queryautism.test',
            ]);

        UserProfile::factory()
            ->for($suspendedUser)
            ->withoutPhone()
            ->create([
                'first_name' => 'Suspended',
                'last_name' => 'Staff',
            ]);

        $neverLoggedIn = User::factory()
            ->verified()
            ->neverLoggedIn()
            ->create([
                'name' => 'Never Logged In',
                'email' => 'never.logged.in@queryautism.test',
                'status' => 'active',
            ]);

        /*
         * Organization memberships
         */
        $group->users()->attach($owner->id, [
            'role' => 'administrator',
            'status' => 'active',
            'is_owner' => true,
            'joined_at' => $now->subYears(3),
        ]);

        $greenValley->users()->attach($owner->id, [
            'role' => 'administrator',
            'status' => 'active',
            'is_owner' => true,
            'joined_at' => $now->subYears(2),
        ]);

        $greenValley->users()->attach($multiManager->id, [
            'role' => 'manager',
            'status' => 'active',
            'is_owner' => false,
            'joined_at' => $now->subYear(),
        ]);

        $rotterdamPadel->users()->attach($multiManager->id, [
            'role' => 'manager',
            'status' => 'active',
            'is_owner' => false,
            'joined_at' => $now->subMonths(8),
        ]);

        $rotterdamPadel->users()->attach($userWithoutProfile->id, [
            'role' => 'reception',
            'status' => 'active',
            'is_owner' => false,
            'joined_at' => $now->subMonths(4),
        ]);

        $greenValley->users()->attach($suspendedUser->id, [
            'role' => 'employee',
            'status' => 'suspended',
            'is_owner' => false,
            'joined_at' => $now->subYear(),
        ]);

        $serenityWellness->users()->attach($neverLoggedIn->id, [
            'role' => 'manager',
            'status' => 'invited',
            'is_owner' => false,
            'invited_at' => $now->subWeek(),
        ]);

        /*
         * Customers
         */
        $pascalMember = Customer::factory()
            ->for($greenValley)
            ->forUser($owner)
            ->create([
                'customer_number' => 'GV-0001',
                'first_name' => 'Pascal',
                'last_name' => 'Member',
                'email' => $owner->email,
                'status' => 'active',
                'source' => 'online',
            ]);

        CustomerProfile::factory()
            ->for($pascalMember)
            ->create([
                'loyalty_tier' => 'gold',
                'last_recalculated_at' => $now,
            ]);

        $greenGuest = Customer::factory()
            ->for($greenValley)
            ->guest()
            ->create([
                'customer_number' => 'GV-0002',
                'first_name' => 'Grace',
                'last_name' => 'Guest',
                'email' => 'grace.guest@example.test',
            ]);

        CustomerProfile::factory()
            ->for($greenGuest)
            ->stale()
            ->create([
                'loyalty_tier' => 'bronze',
            ]);

        $blockedCustomer = Customer::factory()
            ->for($greenValley)
            ->guest()
            ->blocked()
            ->create([
                'customer_number' => 'GV-0003',
                'first_name' => 'Blake',
                'last_name' => 'Blocked',
                'email' => 'blake.blocked@example.test',
            ]);

        CustomerProfile::factory()
            ->for($blockedCustomer)
            ->highRisk()
            ->create();

        $multiGreen = Customer::factory()
            ->for($greenValley)
            ->forUser($multiManager)
            ->create([
                'customer_number' => 'GV-0004',
                'first_name' => 'Mila',
                'last_name' => 'Manager',
                'email' => $multiManager->email,
            ]);

        CustomerProfile::factory()
            ->for($multiGreen)
            ->create([
                'loyalty_tier' => 'silver',
            ]);

        $multiRotterdam = Customer::factory()
            ->for($rotterdamPadel)
            ->forUser($multiManager)
            ->create([
                'customer_number' => 'RP-0001',
                'first_name' => 'Mila',
                'last_name' => 'Manager',
                'email' => 'mila.alias@example.test',
            ]);

        $customerWithoutMembership = Customer::factory()
            ->for($rotterdamPadel)
            ->forUser($userWithoutProfile)
            ->create([
                'customer_number' => 'RP-0002',
                'first_name' => 'Nora',
                'last_name' => 'No Membership',
                'email' => $userWithoutProfile->email,
            ]);

        $overlapCustomer = Customer::factory()
            ->for($greenValley)
            ->guest()
            ->create([
                'customer_number' => 'GV-0005',
                'first_name' => 'Oscar',
                'last_name' => 'Overlap',
                'email' => 'oscar.overlap@example.test',
            ]);

        CustomerProfile::factory()
            ->for($overlapCustomer)
            ->create([
                'loyalty_tier' => 'platinum',
            ]);

        $statusMismatchCustomer = Customer::factory()
            ->for($rotterdamPadel)
            ->guest()
            ->create([
                'customer_number' => 'RP-0003',
                'first_name' => 'Stella',
                'last_name' => 'Status Mismatch',
                'email' => 'stella.mismatch@example.test',
            ]);

        CustomerProfile::factory()
            ->for($statusMismatchCustomer)
            ->create();

        $duplicateGuestA = Customer::factory()
            ->for($greenValley)
            ->guest()
            ->create([
                'customer_number' => 'GV-0006',
                'first_name' => 'Dylan',
                'last_name' => 'Duplicate A',
                'email' => 'duplicate.a@example.test',
            ]);

        $duplicateGuestB = Customer::factory()
            ->for($greenValley)
            ->guest()
            ->create([
                'customer_number' => 'GV-0007',
                'first_name' => 'Daisy',
                'last_name' => 'Duplicate B',
                'email' => 'duplicate.b@example.test',
            ]);

        $crossOrganizationCustomer = Customer::factory()
            ->for($rotterdamPadel)
            ->guest()
            ->create([
                'customer_number' => 'RP-0004',
                'first_name' => 'Cora',
                'last_name' => 'Cross Organization',
                'email' => 'cora.cross@example.test',
            ]);

        /*
         * Membership plans
         */
        $guestPlan = MembershipPlan::factory()
            ->for($greenValley)
            ->active()
            ->free()
            ->create([
                'code' => 'GUEST',
                'name' => 'Guest',
                'priority' => 10,
            ]);

        $standardPlan = MembershipPlan::factory()
            ->for($greenValley)
            ->active()
            ->monthly()
            ->create([
                'code' => 'STANDARD',
                'name' => 'Standard Member',
                'price' => 45,
                'priority' => 50,
            ]);

        $premiumPlan = MembershipPlan::factory()
            ->for($greenValley)
            ->active()
            ->yearly()
            ->create([
                'code' => 'PREMIUM',
                'name' => 'Premium Member',
                'price' => 450,
                'priority' => 100,
            ]);

        MembershipPlan::factory()
            ->for($greenValley)
            ->archived()
            ->yearly()
            ->create([
                'code' => 'LEGACY',
                'name' => 'Legacy Member',
                'price' => 300,
                'valid_from' => $now->subYears(5)->toDateString(),
                'valid_until' => $now->subYear()->toDateString(),
                'priority' => 20,
            ]);

        $padelPlan = MembershipPlan::factory()
            ->for($rotterdamPadel)
            ->active()
            ->monthly()
            ->create([
                'code' => 'PADEL',
                'name' => 'Padel Member',
                'price' => 35,
                'priority' => 50,
            ]);

        $corporatePlan = MembershipPlan::factory()
            ->for($rotterdamPadel)
            ->active()
            ->yearly()
            ->create([
                'code' => 'CORPORATE',
                'name' => 'Corporate Member',
                'price' => 900,
                'priority' => 90,
            ]);

        MembershipPlan::factory()
            ->for($serenityWellness)
            ->create([
                'code' => 'WELLNESS',
                'name' => 'Wellness Member',
                'status' => 'draft',
                'billing_interval' => 'monthly',
                'price' => 75,
                'priority' => 40,
            ]);

        /*
         * Memberships and status history
         */
        $pascalMembership = Membership::factory()
            ->forCustomerAndPlan($pascalMember, $premiumPlan)
            ->active()
            ->autoRenewing()
            ->create([
                'membership_number' => 'GV-MEM-0001',
                'agreed_price' => 425,
            ]);

        $this->createPendingAndActiveHistory(
            $pascalMembership,
            $owner,
            $now->subMonths(3),
        );

        $expiredGuestMembership = Membership::factory()
            ->forCustomerAndPlan($greenGuest, $guestPlan)
            ->expired()
            ->create([
                'membership_number' => 'GV-MEM-0002',
                'agreed_price' => 0,
            ]);

        MembershipStatusHistory::factory()
            ->for($expiredGuestMembership)
            ->expired()
            ->create([
                'effective_at' => $now->subDay(),
            ]);

        $cancelledMembership = Membership::factory()
            ->forCustomerAndPlan($blockedCustomer, $standardPlan)
            ->cancelled()
            ->create([
                'membership_number' => 'GV-MEM-0003',
                'agreed_price' => 45,
            ]);

        MembershipStatusHistory::factory()
            ->for($cancelledMembership)
            ->cancelled()
            ->create([
                'changed_by_user_id' => $owner->id,
                'effective_at' => $now->subWeek(),
            ]);

        $multiGreenMembership = Membership::factory()
            ->forCustomerAndPlan($multiGreen, $standardPlan)
            ->active()
            ->create([
                'membership_number' => 'GV-MEM-0004',
                'agreed_price' => 40,
            ]);

        MembershipStatusHistory::factory()
            ->for($multiGreenMembership)
            ->active()
            ->create([
                'effective_at' => $now->subMonths(3),
            ]);

        $multiRotterdamMembership = Membership::factory()
            ->forCustomerAndPlan($multiRotterdam, $padelPlan)
            ->active()
            ->create([
                'membership_number' => 'RP-MEM-0001',
                'agreed_price' => 35,
            ]);

        MembershipStatusHistory::factory()
            ->for($multiRotterdamMembership)
            ->active()
            ->create([
                'effective_at' => $now->subMonths(3),
            ]);

        $overlapMembershipOne = Membership::factory()
            ->forCustomerAndPlan($overlapCustomer, $standardPlan)
            ->active()
            ->create([
                'membership_number' => 'GV-MEM-0005',
                'starts_at' => $now->subMonths(6),
                'ends_at' => $now->addMonths(6),
                'activated_at' => $now->subMonths(6),
                'agreed_price' => 45,
            ]);

        MembershipStatusHistory::factory()
            ->for($overlapMembershipOne)
            ->active()
            ->create([
                'effective_at' => $now->subMonths(6),
            ]);

        $overlapMembershipTwo = Membership::factory()
            ->forCustomerAndPlan($overlapCustomer, $premiumPlan)
            ->paused()
            ->create([
                'membership_number' => 'GV-MEM-0006',
                'starts_at' => $now->subMonth(),
                'ends_at' => $now->addYear(),
                'activated_at' => $now->subMonth(),
                'agreed_price' => 400,
            ]);

        MembershipStatusHistory::factory()
            ->for($overlapMembershipTwo)
            ->paused()
            ->create([
                'effective_at' => $now->subWeek(),
            ]);

        $statusMismatchMembership = Membership::factory()
            ->forCustomerAndPlan(
                $statusMismatchCustomer,
                $corporatePlan,
            )
            ->active()
            ->create([
                'membership_number' => 'RP-MEM-0002',
                'agreed_price' => 850,
            ]);

        MembershipStatusHistory::factory()
            ->for($statusMismatchMembership)
            ->active()
            ->create([
                'effective_at' => $now->subMonths(3),
            ]);

        MembershipStatusHistory::factory()
            ->for($statusMismatchMembership)
            ->paused()
            ->create([
                'effective_at' => $now->subDay(),
            ]);

        /*
         * External identifiers
         */
        ExternalIdentifier::factory()
            ->forCustomer($pascalMember)
            ->verified()
            ->create([
                'provider' => 'golf_federation',
                'identifier_type' => 'membership_number',
                'identifier_value' => 'GVF-1001',
                'normalized_value' => 'GVF-1001',
            ]);

        ExternalIdentifier::factory()
            ->forCustomer($crossOrganizationCustomer)
            ->verified()
            ->create([
                'provider' => 'golf_federation',
                'identifier_type' => 'membership_number',
                'identifier_value' => 'GVF-1001',
                'normalized_value' => 'GVF-1001',
            ]);

        ExternalIdentifier::factory()
            ->forCustomer($duplicateGuestA)
            ->unverified()
            ->create([
                'provider' => 'booking_partner',
                'identifier_type' => 'customer_number',
                'identifier_value' => 'DUP-2000',
                'normalized_value' => 'DUP-2000',
            ]);

        ExternalIdentifier::factory()
            ->forCustomer($duplicateGuestB)
            ->unverified()
            ->create([
                'provider' => 'booking_partner',
                'identifier_type' => 'customer_number',
                'identifier_value' => ' dup-2000 ',
                'normalized_value' => 'DUP-2000',
            ]);

        ExternalIdentifier::factory()
            ->forCustomer($blockedCustomer)
            ->manuallyVerified()
            ->create([
                'provider' => 'crm',
                'identifier_type' => 'customer_number',
                'identifier_value' => 'CRM-3001',
                'normalized_value' => 'CRM-3001',
            ]);

        /*
         * Keep references intentionally used by the learning scenarios.
         */
        unset(
            $customerWithoutMembership,
            $userWithoutProfile,
            $multiRotterdamMembership,
        );
    }

    private function createPendingAndActiveHistory(
        Membership $membership,
        User $changedBy,
        CarbonImmutable $activatedAt,
    ): void {
        MembershipStatusHistory::factory()
            ->for($membership)
            ->create([
                'from_status' => null,
                'to_status' => 'pending',
                'effective_at' => $activatedAt->subDay(),
            ]);

        MembershipStatusHistory::factory()
            ->for($membership)
            ->active()
            ->create([
                'changed_by_user_id' => $changedBy->id,
                'effective_at' => $activatedAt,
            ]);
    }
}
