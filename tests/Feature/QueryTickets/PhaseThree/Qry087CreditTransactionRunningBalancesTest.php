<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry087CreditTransactionRunningBalances;

class Qry087CreditTransactionRunningBalancesTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_credit_running_balances(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry087CreditTransactionRunningBalances::class,
        );

        $this->assertSame([
            'GV-0001',
            'GV-0001',
            'GV-0001',
            'GV-0002',
            'GV-0002',
            'RP-0001',
            'RP-0001',
        ], $results->pluck('customer_number')->all());

        $this->assertSame([
            'grant',
            'spend',
            'refund',
            'grant',
            'spend',
            'grant',
            'spend',
        ], $results->pluck('type')->all());

        $this->assertSame([
            100.0,
            20.0,
            30.0,
            30.0,
            20.0,
            50.0,
            20.0,
        ], $results->map(
            fn ($row) => (float) $row->running_balance,
        )->all());

        $this->assertSame([
            100.0,
            20.0,
            30.0,
            30.0,
            20.0,
            50.0,
            20.0,
        ], $results->map(
            fn ($row) => (float) $row->balance_after,
        )->all());

        $this->assertResultColumns($results, [
            'customer_number',
            'type',
            'amount',
            'balance_after',
            'running_balance',
            'occurred_at',
        ]);
    }
}
