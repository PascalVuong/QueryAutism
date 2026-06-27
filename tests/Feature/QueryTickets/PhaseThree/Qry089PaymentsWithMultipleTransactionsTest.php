<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use App\QueryTickets\PhaseThree\Qry089PaymentsWithMultipleTransactions;

class Qry089PaymentsWithMultipleTransactionsTest extends PhaseThreeQueryTicketTestCase
{
    public function test_it_returns_payments_with_multiple_transactions(): void
    {
        $results = $this->runPhaseThreeTicket(
            Qry089PaymentsWithMultipleTransactions::class,
        );

        $this->assertSame([
            'PAY-RP-0001',
            'PAY-GV-0001',
            'PAY-SW-0001',
        ], $results->pluck('provider_reference')->all());

        $this->assertSame([
            3,
            2,
            2,
        ], $results->pluck('transactions_count')->map(
            fn ($count) => (int) $count,
        )->all());

        $this->assertExactColumns($results, [
            'id',
            'provider_reference',
            'status',
            'amount',
            'transactions_count',
        ]);
    }
}
