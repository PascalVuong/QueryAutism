<?php

namespace Tests\Feature\Data;

use App\Models\CreditAccount;
use App\Models\Payment;
use App\Models\Reservation;
use Database\Seeders\PhaseOneScenarioSeeder;
use Database\Seeders\PhaseThreeScenarioSeeder;
use Database\Seeders\PhaseTwoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseThreeScenarioSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PhaseOneScenarioSeeder::class,
            PhaseTwoScenarioSeeder::class,
            PhaseThreeScenarioSeeder::class,
        ]);
    }

    public function test_seeder_creates_expected_phase_three_dataset(): void
    {
        $this->assertDatabaseCount('price_rules', 7);
        $this->assertDatabaseCount('reservation_charges', 9);
        $this->assertDatabaseCount('payments', 7);
        $this->assertDatabaseCount('payment_transactions', 11);
        $this->assertDatabaseCount('refunds', 2);
        $this->assertDatabaseCount('credit_accounts', 3);
        $this->assertDatabaseCount('credit_transactions', 7);
    }

    public function test_seeder_contains_required_payment_scenarios(): void
    {
        $underpaid = Reservation::query()
            ->where('reference_number', 'GV-RES-0004')
            ->with('payments')
            ->firstOrFail();

        $successfulPaidAmount = $underpaid->payments
            ->whereIn('status', [
                'paid',
                'partially_refunded',
                'refunded',
            ])
            ->sum('amount');

        $this->assertSame(100.0, (float) $underpaid->total);
        $this->assertSame(60.0, (float) $successfulPaidAmount);

        $failedPayment = Payment::query()
            ->where('provider_reference', 'PAY-RP-0002')
            ->with('transactions')
            ->firstOrFail();

        $this->assertSame('failed', $failedPayment->status);
        $this->assertSame(
            ['failure'],
            $failedPayment->transactions->pluck('type')->all(),
        );

        $partialRefund = Payment::query()
            ->where('provider_reference', 'PAY-RP-0001')
            ->firstOrFail();

        $this->assertSame('partially_refunded', $partialRefund->status);
        $this->assertSame('20.00', $partialRefund->refunded_amount);

        $fullRefund = Payment::query()
            ->where('provider_reference', 'PAY-SW-0001')
            ->firstOrFail();

        $this->assertSame('refunded', $fullRefund->status);
        $this->assertSame($fullRefund->amount, $fullRefund->refunded_amount);
    }

    public function test_seeder_contains_credit_balance_mismatch(): void
    {
        $account = CreditAccount::query()
            ->whereHas('customer', function ($query) {
                $query->where('customer_number', 'GV-0002');
            })
            ->with('transactions')
            ->firstOrFail();

        $this->assertSame('15.00', $account->balance);
        $this->assertSame(
            20.0,
            (float) $account->transactions->sum('amount'),
        );
        $this->assertTrue($account->expires_at->isPast());
        $this->assertSame('frozen', $account->status);
    }
}
