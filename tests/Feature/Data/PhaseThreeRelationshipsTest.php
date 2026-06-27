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
use App\Models\ReservationItem;
use App\Models\Resource;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseThreeRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_phase_three_relationships_are_configured(): void
    {
        $venue = Venue::factory()->create();
        $customer = Customer::factory()
            ->for($venue->organization)
            ->create();
        $resource = Resource::factory()
            ->for(\App\Models\Facility::factory()->for($venue))
            ->create();
        $reservation = Reservation::factory()
            ->forCustomerAtVenue($customer, $venue)
            ->create();
        $item = ReservationItem::factory()
            ->forReservationAndResource($reservation, $resource)
            ->create();

        $rule = PriceRule::factory()
            ->for($venue->organization)
            ->active()
            ->create([
                'venue_id' => $venue->id,
                'resource_id' => $resource->id,
            ]);

        $charge = ReservationCharge::factory()
            ->for($reservation)
            ->create([
                'reservation_item_id' => $item->id,
                'price_rule_id' => $rule->id,
            ]);

        $payment = Payment::factory()
            ->forReservation($reservation)
            ->paid()
            ->create();

        $transaction = PaymentTransaction::factory()
            ->forPayment($payment)
            ->create();

        $refund = Refund::factory()
            ->forPayment($payment)
            ->succeeded()
            ->create();

        $account = CreditAccount::factory()
            ->forCustomer($customer)
            ->create();

        $creditTransaction = CreditTransaction::factory()
            ->forAccount($account)
            ->create([
                'reservation_id' => $reservation->id,
            ]);

        $this->assertTrue(
            $venue->organization->priceRules->contains($rule),
        );
        $this->assertTrue(
            $venue->organization->payments->contains($payment),
        );
        $this->assertTrue(
            $venue->organization->creditAccounts->contains($account),
        );

        $this->assertTrue($reservation->charges->contains($charge));
        $this->assertTrue($reservation->payments->contains($payment));
        $this->assertTrue($reservation->refunds->contains($refund));
        $this->assertTrue($item->charges->contains($charge));

        $this->assertTrue($payment->transactions->contains($transaction));
        $this->assertTrue($payment->refunds->contains($refund));

        $this->assertTrue($customer->payments->contains($payment));
        $this->assertTrue($customer->creditAccounts->contains($account));
        $this->assertTrue(
            $account->transactions->contains($creditTransaction),
        );
        $this->assertTrue(
            $creditTransaction->reservation->is($reservation),
        );
    }
}
