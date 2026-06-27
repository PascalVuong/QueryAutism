<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\Models\Customer;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\QueryTickets\PhaseOne\Qry033UpcomingMemberships;
use Carbon\CarbonImmutable;
use Database\Seeders\PhaseOneScenarioSeeder;
use Illuminate\Support\Facades\DB;
use LogicException;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry033UpcomingMembershipsTest extends QueryTicketTestCase
{
    public function test_it_returns_memberships_starting_in_the_future(): void
    {
        $this->travelTo(
            CarbonImmutable::parse('2026-06-27 12:00:00'),
        );

        $this->seed(PhaseOneScenarioSeeder::class);

        $customer = Customer::query()
            ->where('customer_number', 'GV-0001')
            ->firstOrFail();

        $plan = MembershipPlan::query()
            ->where('organization_id', $customer->organization_id)
            ->where('code', 'STANDARD')
            ->firstOrFail();

        Membership::factory()
            ->forCustomerAndPlan($customer, $plan)
            ->create([
                'membership_number' => 'TEST-UPCOMING-001',
                'status' => 'pending',
                'starts_at' => now()->addDays(10),
                'ends_at' => now()->addYear(),
                'activated_at' => null,
                'cancelled_at' => null,
                'cancellation_reason' => null,
                'auto_renew' => false,
            ]);

        Membership::factory()
            ->forCustomerAndPlan($customer, $plan)
            ->create([
                'membership_number' => 'TEST-STARTS-NOW',
                'status' => 'pending',
                'starts_at' => now(),
                'ends_at' => now()->addYear(),
                'activated_at' => null,
                'cancelled_at' => null,
                'cancellation_reason' => null,
                'auto_renew' => false,
            ]);

        try {
            $results = app(Qry033UpcomingMemberships::class)->run();
        } catch (LogicException $exception) {
            $this->markTestIncomplete($exception->getMessage());
        }

        $expected = DB::table('memberships')
            ->whereNull('deleted_at')
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->orderBy('membership_number')
            ->pluck('membership_number')
            ->all();

        $this->assertSame(
            $expected,
            $results->pluck('membership_number')->all(),
        );

        $this->assertContains(
            'TEST-UPCOMING-001',
            $results->pluck('membership_number')->all(),
        );

        $this->assertNotContains(
            'TEST-STARTS-NOW',
            $results->pluck('membership_number')->all(),
        );

        $this->assertExactColumns($results, [
            'id',
            'customer_id',
            'membership_number',
            'status',
            'starts_at',
        ]);
    }
}
