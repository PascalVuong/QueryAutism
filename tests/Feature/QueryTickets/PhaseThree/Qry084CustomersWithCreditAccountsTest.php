<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry084CustomersWithCreditAccounts;

class Qry084CustomersWithCreditAccountsTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_customers_with_credit_accounts(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry084CustomersWithCreditAccounts::class,
        );

        $this->assertSame([
            'GV-0001',
            'GV-0002',
            'RP-0001',
        ], $results->pluck('customer_number')->all());

        $balances = [
            'GV-0001' => ['30.00'],
            'GV-0002' => ['15.00'],
            'RP-0001' => ['20.00'],
        ];

        foreach ($results as $customer) {
            $this->assertTrue(
                $customer->relationLoaded('creditAccounts'),
            );
            $this->assertSame(
                $balances[$customer->customer_number],
                $customer->creditAccounts->pluck('balance')->all(),
            );
        }

        $this->assertExactColumns($results, [
            'id',
            'customer_number',
            'first_name',
            'last_name',
        ]);
    }
}
