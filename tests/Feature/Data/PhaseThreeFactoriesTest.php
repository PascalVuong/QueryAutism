<?php

namespace Tests\Feature\Data;

use App\Models\CreditAccount;
use App\Models\CreditTransaction;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Models\PriceRule;
use App\Models\Refund;
use App\Models\Reservation;
use App\Models\ReservationCharge;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseThreeFactoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_three_factories_and_states_create_valid_data(): void
    {
        $venue = Venue::factory()->create();
        $customer = Customer::factory()
            ->for($venue->organization)
            ->create();
        $reservation = Reservation::factory()
            ->forCustomerAtVenue($customer, $venue)
            ->create();

        $rule = PriceRule::factory()
            ->for($venue->organization)
            ->active()
            ->percentage(10)
            ->create([
                'venue_id' => $venue->id,
            ]);

        $charge = ReservationCharge::factory()
            ->for($reservation)
            ->discount()
            ->create([
                'price_rule_id' => $rule->id,
            ]);

        $payment = Payment::factory()
            ->forReservation($reservation)
            ->paid()
            ->create([
                'amount' => 100,
            ]);

        $transaction = PaymentTransaction::factory()
            ->forPayment($payment)
            ->create([
                'amount' => 100,
            ]);

        $refund = Refund::factory()
            ->forPayment($payment)
            ->succeeded()
            ->create([
                'amount' => 25,
            ]);

        $account = CreditAccount::factory()
            ->forCustomer($customer)
            ->frozen()
            ->expired()
            ->create([
                'balance' => 20,
            ]);

        $creditTransaction = CreditTransaction::factory()
            ->forAccount($account)
            ->spend(5)
            ->create([
                'balance_after' => 15,
            ]);

        $this->assertSame('active', $rule->status);
        $this->assertSame('percentage', $rule->type);
        $this->assertSame('10.0000', $rule->percentage);

        $this->assertTrue($charge->reservation->is($reservation));
        $this->assertTrue($charge->priceRule->is($rule));
        $this->assertSame('credit', $charge->direction);

        $this->assertTrue($payment->reservation->is($reservation));
        $this->assertSame('paid', $payment->status);
        $this->assertTrue($transaction->payment->is($payment));
        $this->assertTrue($refund->payment->is($payment));

        $this->assertTrue($account->customer->is($customer));
        $this->assertSame('frozen', $account->status);
        $this->assertTrue($account->expires_at->isPast());
        $this->assertSame('-5.00', $creditTransaction->amount);
    }
}
