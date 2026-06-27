<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry047DuplicateExternalIdentifiers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry047DuplicateExternalIdentifiersTest extends QueryTicketTestCase
{
    public function test_it_returns_duplicate_external_identifiers(): void
    {
        $results = $this->runTicket(
            Qry047DuplicateExternalIdentifiers::class,
        );

        $this->assertSame([
            [
                'provider' => 'booking_partner',
                'identifier_type' => 'customer_number',
                'normalized_value' => 'DUP-2000',
                'duplicate_count' => 2,
            ],
            [
                'provider' => 'golf_federation',
                'identifier_type' => 'membership_number',
                'normalized_value' => 'GVF-1001',
                'duplicate_count' => 2,
            ],
        ], $results->map(fn ($row) => [
            'provider' => $row->provider,
            'identifier_type' => $row->identifier_type,
            'normalized_value' => $row->normalized_value,
            'duplicate_count' => (int) $row->duplicate_count,
        ])->all());

        $this->assertResultColumns($results, [
            'provider',
            'identifier_type',
            'normalized_value',
            'duplicate_count',
        ]);
    }
}