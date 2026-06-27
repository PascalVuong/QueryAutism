<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\Models\Customer;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\QueryTickets\PhaseOne\Qry035CancelledMembershipsWithReason;
use Database\Seeders\PhaseOneScenarioSeeder;
use Illuminate\Support\Facades\DB;
use LogicException;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry035CancelledMembershipsWithReasonTest extends QueryTicketTestCase
{
    public function test_it_returns_cancelled_memberships_with_a_reason(): void
    {
        $this->seed(PhaseOneScenarioSeeder::class);

        $customer = Customer::query()
            ->where('customer_number', 'GV-0003')
            ->firstOrFail();

        $plan = MembershipPlan::query()
            ->where('organization_id', $customer->organization_id)
            ->where('code', 'STANDARD')
            ->firstOrFail();

        Membership::factory()
            ->forCustomerAndPlan($customer, $plan)
            ->cancelled()
            ->create([
                'membership_number' => 'TEST-CANCELLED-REASON',
                'cancellation_reason' => 'Requested by customer',
            ]);

        Membership::factory()
            ->forCustomerAndPlan($customer, $plan)
            ->cancelled()
            ->create([
                'membership_number' => 'TEST-CANCELLED-NO-REASON',
                'cancellation_reason' => null,
            ]);

        try {
            $results = app(
                Qry035CancelledMembershipsWithReason::class,
            )->run();
        } catch (LogicException $exception) {
            $this->markTestIncomplete($exception->getMessage());
        }

        $expected = DB::table('memberships')
            ->whereNull('deleted_at')
            ->where('status', 'cancelled')
            ->whereNotNull('cancellation_reason')
            ->where('cancellation_reason', '<>', '')
            ->orderBy('membership_number')
            ->pluck('membership_number')
            ->all();

        $actualNumbers = $results
            ->pluck('membership_number')
            ->all();

        $this->assertSame($expected, $actualNumbers);
        $this->assertContains('TEST-CANCELLED-REASON', $actualNumbers);
        $this->assertNotContains(
            'TEST-CANCELLED-NO-REASON',
            $actualNumbers,
        );

        $this->assertTrue(
            $results->every(
                fn ($membership) =>
                    $membership->status === 'cancelled'
                    && filled($membership->cancellation_reason),
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'membership_number',
            'status',
            'cancelled_at',
            'cancellation_reason',
        ]);
    }
}
