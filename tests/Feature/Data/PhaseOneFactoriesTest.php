<?php

namespace Tests\Feature\Data;

use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\ExternalIdentifier;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\MembershipStatusHistory;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseOneFactoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_one_factories_and_states_create_valid_data(): void
    {
        $organization = Organization::factory()
            ->active()
            ->create();

        $user = User::factory()
            ->suspended()
            ->unverified()
            ->neverLoggedIn()
            ->create();

        $profile = UserProfile::factory()
            ->for($user)
            ->marketingOptIn()
            ->create();

        $customer = Customer::factory()
            ->for($organization)
            ->forUser($user)
            ->create();

        $customerProfile = CustomerProfile::factory()
            ->for($customer)
            ->stale()
            ->create();

        $plan = MembershipPlan::factory()
            ->for($organization)
            ->active()
            ->yearly()
            ->create();

        $membership = Membership::factory()
            ->forCustomerAndPlan($customer, $plan)
            ->active()
            ->autoRenewing()
            ->create();

        $history = MembershipStatusHistory::factory()
            ->for($membership)
            ->active()
            ->create();

        $identifier = ExternalIdentifier::factory()
            ->forCustomer($customer)
            ->verified()
            ->create();

        $this->assertSame('active', $organization->status);
        $this->assertSame('suspended', $user->status);
        $this->assertNull($user->email_verified_at);
        $this->assertNull($user->last_login_at);
        $this->assertTrue($profile->marketing_consent);

        $this->assertTrue($customer->organization->is($organization));
        $this->assertTrue($customer->user->is($user));
        $this->assertTrue($customerProfile->customer->is($customer));

        $this->assertTrue($plan->organization->is($organization));
        $this->assertTrue($membership->customer->is($customer));
        $this->assertTrue($membership->membershipPlan->is($plan));
        $this->assertSame(
            $organization->id,
            $membership->organization_id,
        );
        $this->assertSame('active', $membership->status);
        $this->assertTrue($membership->auto_renew);

        $this->assertSame('active', $history->to_status);
        $this->assertNotNull($identifier->verified_at);
        $this->assertSame(
            $organization->id,
            $identifier->organization_id,
        );
    }

    public function test_common_factory_states_are_available(): void
    {
        $organization = Organization::factory()->create();

        $guest = Customer::factory()
            ->for($organization)
            ->guest()
            ->create();

        $blocked = Customer::factory()
            ->for($organization)
            ->blocked()
            ->create();

        $plan = MembershipPlan::factory()
            ->for($organization)
            ->active()
            ->free()
            ->create();

        $expiredMembership = Membership::factory()
            ->forCustomerAndPlan($guest, $plan)
            ->expired()
            ->create();

        $unverifiedIdentifier = ExternalIdentifier::factory()
            ->forCustomer($blocked)
            ->unverified()
            ->create();

        $this->assertNull($guest->user_id);
        $this->assertSame('guest', $guest->source);
        $this->assertSame('blocked', $blocked->status);
        $this->assertSame('0.00', $plan->price);
        $this->assertSame('expired', $expiredMembership->status);
        $this->assertTrue($expiredMembership->ends_at->isPast());
        $this->assertNull($unverifiedIdentifier->verified_at);
    }
}
