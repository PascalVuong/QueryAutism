<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry085ExpiredCreditAccountsWithBalance;

class Qry085ExpiredCreditAccountsWithBalanceTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_expired_accounts_with_balance(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry085ExpiredCreditAccountsWithBalance::class,
        );

        $this->assertCount(1, $results);

        $account = $results->first();

        $this->assertSame('frozen', $account->status);
        $this->assertSame('15.00', $account->balance);
        $this->assertTrue($account->expires_at->isPast());
        $this->assertTrue($account->relationLoaded('customer'));
        $this->assertSame(
            'GV-0002',
            $account->customer->customer_number,
        );

        $this->assertExactColumns($results, [
            'id',
            'customer_id',
            'status',
            'balance',
            'expires_at',
        ]);
    }
}
