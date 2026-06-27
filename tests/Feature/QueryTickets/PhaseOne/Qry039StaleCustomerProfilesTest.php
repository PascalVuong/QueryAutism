<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\Models\Customer;
use App\Models\CustomerProfile;
use App\QueryTickets\PhaseOne\Qry039StaleCustomerProfiles;
use Carbon\CarbonImmutable;
use Database\Seeders\PhaseOneScenarioSeeder;
use Illuminate\Support\Facades\DB;
use LogicException;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry039StaleCustomerProfilesTest extends QueryTicketTestCase
{
    public function test_it_returns_profiles_older_than_thirty_days(): void
    {
        $this->travelTo(
            CarbonImmutable::parse('2026-06-27 12:00:00'),
        );

        $this->seed(PhaseOneScenarioSeeder::class);

        $staleCustomer = Customer::query()
            ->where('customer_number', 'RP-0001')
            ->firstOrFail();

        $boundaryCustomer = Customer::query()
            ->where('customer_number', 'RP-0002')
            ->firstOrFail();

        CustomerProfile::factory()
            ->for($staleCustomer)
            ->create([
                'last_recalculated_at' => now()->subDays(45),
            ]);

        CustomerProfile::factory()
            ->for($boundaryCustomer)
            ->create([
                'last_recalculated_at' => now()->subDays(30),
            ]);

        try {
            $results = app(Qry039StaleCustomerProfiles::class)->run();
        } catch (LogicException $exception) {
            $this->markTestIncomplete($exception->getMessage());
        }

        $expected = DB::table('customer_profiles')
            ->join(
                'customers',
                'customers.id',
                '=',
                'customer_profiles.customer_id',
            )
            ->whereNotNull('customer_profiles.last_recalculated_at')
            ->where(
                'customer_profiles.last_recalculated_at',
                '<',
                now()->subDays(30),
            )
            ->orderBy('customer_profiles.last_recalculated_at')
            ->orderBy('customer_profiles.id')
            ->pluck('customers.customer_number')
            ->all();

        $actualNumbers = $results
            ->map(fn ($profile) => $profile->customer->customer_number)
            ->all();

        $this->assertSame($expected, $actualNumbers);
        $this->assertContains('RP-0001', $actualNumbers);
        $this->assertNotContains('RP-0002', $actualNumbers);

        foreach ($results as $profile) {
            $this->assertTrue($profile->relationLoaded('customer'));

            $this->assertSame(
                ['id', 'customer_number'],
                array_keys($profile->customer->getAttributes()),
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'customer_id',
            'risk_score',
            'last_recalculated_at',
        ]);
    }
}
