<?php

namespace Database\Seeders;

use App\Models\CreditAccount;
use App\Models\CreditTransaction;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Models\PriceRule;
use App\Models\Refund;
use App\Models\Reservation;
use App\Models\ReservationCharge;
use App\Models\Resource;
use App\Models\User;
use App\Models\Venue;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class PhaseThreeScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $now = CarbonImmutable::now()->startOfMinute();

        $owner = User::query()
            ->where('email', 'owner@queryautism.test')
            ->firstOrFail();

        $greenVenue = Venue::query()
            ->where('slug', 'green-valley-main-venue')
            ->firstOrFail();

        $rotterdamVenue = Venue::query()
            ->where('slug', 'rotterdam-padel-hall')
            ->firstOrFail();

        $serenityVenue = Venue::query()
            ->where('slug', 'serenity-spa')
            ->firstOrFail();

        $northCourse = Resource::query()
            ->where('code', 'NORTH')
            ->firstOrFail();

        $courtOne = Resource::query()
            ->where('code', 'COURT-1')
            ->firstOrFail();

        $treatmentRoom = Resource::query()
            ->where('code', 'ROOM-1')
            ->firstOrFail();

        $greenBase = $this->fixedRule(
            $greenVenue,
            $northCourse,
            'GREEN-BASE',
            'Green fee base price',
            100,
            100,
        );

        $greenSurcharge = $this->fixedRule(
            $greenVenue,
            $northCourse,
            'GREEN-SURCHARGE',
            'Peak time surcharge',
            20,
            80,
            true,
        );

        $greenDiscount = $this->fixedRule(
            $greenVenue,
            $northCourse,
            'GREEN-DISCOUNT',
            'Member discount',
            10,
            90,
            true,
        );

        $padelBase = $this->fixedRule(
            $rotterdamVenue,
            $courtOne,
            'PADEL-BASE',
            'Padel court price',
            80,
            100,
        );

        $wellnessBase = $this->fixedRule(
            $serenityVenue,
            $treatmentRoom,
            'WELLNESS-BASE',
            'Treatment room price',
            90,
            100,
        );

        PriceRule::factory()
            ->for($greenVenue->organization)
            ->archived()
            ->create([
                'venue_id' => $greenVenue->id,
                'code' => 'LEGACY-DISCOUNT',
                'name' => 'Legacy discount',
                'type' => 'percentage',
                'amount' => null,
                'percentage' => 15,
                'starts_at' => $now->subYears(2),
                'ends_at' => $now->subYear(),
            ]);

        PriceRule::factory()
            ->for($rotterdamVenue->organization)
            ->active()
            ->create([
                'venue_id' => $rotterdamVenue->id,
                'code' => 'FUTURE-SURCHARGE',
                'name' => 'Future tournament surcharge',
                'amount' => 25,
                'starts_at' => $now->addMonth(),
                'ends_at' => $now->addMonths(2),
            ]);

        $gvOne = $this->reservation('GV-RES-0001');
        $gvTwo = $this->reservation('GV-RES-0002');
        $rpOne = $this->reservation('RP-RES-0001');
        $gvCancelled = $this->reservation('GV-RES-0003');
        $rpFailed = $this->reservation('RP-RES-0002');
        $swRefunded = $this->reservation('SW-RES-0001');
        $gvUnderpaid = $this->reservation('GV-RES-0004');

        $this->charge($gvOne, $greenBase, 'base', 'debit', 100);
        $this->charge(
            $gvOne,
            $greenSurcharge,
            'surcharge',
            'debit',
            20,
        );

        $this->charge($gvTwo, $greenBase, 'base', 'debit', 70);
        $this->charge(
            $gvTwo,
            $greenDiscount,
            'discount',
            'credit',
            10,
        );

        $this->charge($rpOne, $padelBase, 'base', 'debit', 80);
        $this->charge($gvCancelled, $greenBase, 'base', 'debit', 150);
        $this->charge($rpFailed, $padelBase, 'base', 'debit', 80);
        $this->charge($swRefunded, $wellnessBase, 'base', 'debit', 90);
        $this->charge($gvUnderpaid, $greenBase, 'base', 'debit', 100);

        $gvOnePayment = $this->payment(
            $gvOne,
            'paid',
            'card',
            120,
            0,
            'PAY-GV-0001',
            $now->subHours(10),
        );
        $this->transaction(
            $gvOnePayment,
            'authorization',
            'succeeded',
            120,
            $now->subHours(11),
            'TX-GV-0001-AUTH',
        );
        $this->transaction(
            $gvOnePayment,
            'capture',
            'succeeded',
            120,
            $now->subHours(10),
            'TX-GV-0001-CAPTURE',
        );

        $gvTwoPayment = $this->payment(
            $gvTwo,
            'paid',
            'cash',
            60,
            0,
            'PAY-GV-0002',
            $now->subHours(6),
        );
        $this->transaction(
            $gvTwoPayment,
            'capture',
            'succeeded',
            60,
            $now->subHours(6),
            'TX-GV-0002-CAPTURE',
        );

        $rpOnePayment = $this->payment(
            $rpOne,
            'partially_refunded',
            'card',
            80,
            20,
            'PAY-RP-0001',
            $now->subDays(2),
        );
        $this->transaction(
            $rpOnePayment,
            'authorization',
            'succeeded',
            80,
            $now->subDays(2)->subHour(),
            'TX-RP-0001-AUTH',
        );
        $this->transaction(
            $rpOnePayment,
            'capture',
            'succeeded',
            80,
            $now->subDays(2),
            'TX-RP-0001-CAPTURE',
        );
        $this->transaction(
            $rpOnePayment,
            'refund',
            'succeeded',
            20,
            $now->subDay(),
            'TX-RP-0001-REFUND',
        );
        $this->refund(
            $rpOnePayment,
            20,
            'Partial goodwill refund',
            $owner,
            $now->subDay(),
            'REF-RP-0001',
        );

        $rpFailedPayment = $this->payment(
            $rpFailed,
            'failed',
            'card',
            80,
            0,
            'PAY-RP-0002',
            null,
            $now->subHours(3),
        );
        $this->transaction(
            $rpFailedPayment,
            'failure',
            'failed',
            80,
            $now->subHours(3),
            'TX-RP-0002-FAIL',
            'Card declined',
        );

        $swPayment = $this->payment(
            $swRefunded,
            'refunded',
            'card',
            90,
            90,
            'PAY-SW-0001',
            $now->subDays(2),
        );
        $this->transaction(
            $swPayment,
            'capture',
            'succeeded',
            90,
            $now->subDays(2),
            'TX-SW-0001-CAPTURE',
        );
        $this->transaction(
            $swPayment,
            'refund',
            'succeeded',
            90,
            $now->subDay(),
            'TX-SW-0001-REFUND',
        );
        $this->refund(
            $swPayment,
            90,
            'Full no-show refund',
            null,
            $now->subDay(),
            'REF-SW-0001',
        );

        $gvPartialPayment = $this->payment(
            $gvUnderpaid,
            'paid',
            'card',
            60,
            0,
            'PAY-GV-0004-A',
            $now->subHours(2),
        );
        $this->transaction(
            $gvPartialPayment,
            'capture',
            'succeeded',
            60,
            $now->subHours(2),
            'TX-GV-0004-CAPTURE',
        );

        $gvFailedRemainder = $this->payment(
            $gvUnderpaid,
            'failed',
            'card',
            40,
            0,
            'PAY-GV-0004-B',
            null,
            $now->subHour(),
        );
        $this->transaction(
            $gvFailedRemainder,
            'failure',
            'failed',
            40,
            $now->subHour(),
            'TX-GV-0004-FAIL',
            'Insufficient funds',
        );

        $pascalAccount = $this->creditAccount('GV-0001', 'active', 30);
        $this->creditTransaction(
            $pascalAccount,
            null,
            'grant',
            100,
            100,
            $now->subMonths(3),
        );
        $this->creditTransaction(
            $pascalAccount,
            $gvOne,
            'spend',
            -80,
            20,
            $now->subDays(2),
        );
        $this->creditTransaction(
            $pascalAccount,
            $gvOne,
            'refund',
            10,
            30,
            $now->subDay(),
        );

        $milaAccount = $this->creditAccount('RP-0001', 'active', 20);
        $this->creditTransaction(
            $milaAccount,
            null,
            'grant',
            50,
            50,
            $now->subMonths(2),
        );
        $this->creditTransaction(
            $milaAccount,
            $rpOne,
            'spend',
            -30,
            20,
            $now->subMonth(),
        );

        $graceAccount = $this->creditAccount(
            'GV-0002',
            'frozen',
            15,
            $now->subDay(),
        );
        $this->creditTransaction(
            $graceAccount,
            null,
            'grant',
            30,
            30,
            $now->subMonths(2),
        );
        $this->creditTransaction(
            $graceAccount,
            $gvTwo,
            'spend',
            -10,
            20,
            $now->subMonth(),
        );
    }

    private function reservation(string $reference): Reservation
    {
        return Reservation::query()
            ->where('reference_number', $reference)
            ->firstOrFail();
    }

    private function fixedRule(
        Venue $venue,
        Resource $resource,
        string $code,
        string $name,
        float|int $amount,
        int $priority,
        bool $stackable = false,
    ): PriceRule {
        return PriceRule::factory()
            ->for($venue->organization)
            ->active()
            ->create([
                'venue_id' => $venue->id,
                'resource_id' => $resource->id,
                'code' => $code,
                'name' => $name,
                'type' => 'fixed',
                'amount' => $amount,
                'percentage' => null,
                'priority' => $priority,
                'is_stackable' => $stackable,
            ]);
    }

    private function charge(
        Reservation $reservation,
        PriceRule $rule,
        string $type,
        string $direction,
        float|int $amount,
    ): ReservationCharge {
        return ReservationCharge::factory()
            ->for($reservation)
            ->create([
                'price_rule_id' => $rule->id,
                'type' => $type,
                'direction' => $direction,
                'description' => $rule->name,
                'unit_amount' => $amount,
                'total_amount' => $amount,
                'currency' => $reservation->currency,
            ]);
    }

    private function payment(
        Reservation $reservation,
        string $status,
        string $method,
        float|int $amount,
        float|int $refundedAmount,
        string $providerReference,
        ?CarbonImmutable $paidAt = null,
        ?CarbonImmutable $failedAt = null,
    ): Payment {
        return Payment::factory()
            ->forReservation($reservation)
            ->create([
                'status' => $status,
                'method' => $method,
                'amount' => $amount,
                'refunded_amount' => $refundedAmount,
                'provider_reference' => $providerReference,
                'paid_at' => $paidAt,
                'failed_at' => $failedAt,
            ]);
    }

    private function transaction(
        Payment $payment,
        string $type,
        string $status,
        float|int $amount,
        CarbonImmutable $occurredAt,
        string $providerReference,
        ?string $failureReason = null,
    ): PaymentTransaction {
        return PaymentTransaction::factory()
            ->forPayment($payment)
            ->create([
                'type' => $type,
                'status' => $status,
                'amount' => $amount,
                'provider_reference' => $providerReference,
                'failure_reason' => $failureReason,
                'occurred_at' => $occurredAt,
            ]);
    }

    private function refund(
        Payment $payment,
        float|int $amount,
        string $reason,
        ?User $requestedBy,
        CarbonImmutable $processedAt,
        string $providerReference,
    ): Refund {
        return Refund::factory()
            ->forPayment($payment)
            ->succeeded()
            ->create([
                'requested_by_user_id' => $requestedBy?->id,
                'amount' => $amount,
                'reason' => $reason,
                'provider_reference' => $providerReference,
                'processed_at' => $processedAt,
            ]);
    }

    private function creditAccount(
        string $customerNumber,
        string $status,
        float|int $balance,
        ?CarbonImmutable $expiresAt = null,
    ): CreditAccount {
        $customer = Customer::query()
            ->where('customer_number', $customerNumber)
            ->firstOrFail();

        return CreditAccount::factory()
            ->forCustomer($customer)
            ->create([
                'status' => $status,
                'balance' => $balance,
                'expires_at' => $expiresAt,
            ]);
    }

    private function creditTransaction(
        CreditAccount $account,
        ?Reservation $reservation,
        string $type,
        float|int $amount,
        float|int $balanceAfter,
        CarbonImmutable $occurredAt,
    ): CreditTransaction {
        return CreditTransaction::factory()
            ->forAccount($account)
            ->create([
                'reservation_id' => $reservation?->id,
                'type' => $type,
                'amount' => $amount,
                'balance_after' => $balanceAfter,
                'occurred_at' => $occurredAt,
            ]);
    }
}
