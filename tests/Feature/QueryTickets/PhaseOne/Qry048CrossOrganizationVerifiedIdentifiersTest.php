<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry048CrossOrganizationVerifiedIdentifiers;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry048CrossOrganizationVerifiedIdentifiersTest extends QueryTicketTestCase
{
    public function test_it_returns_verified_identifiers_used_by_multiple_organizations(): void
    {
        $results = $this->runTicket(
            Qry048CrossOrganizationVerifiedIdentifiers::class,
        );

        $this->assertSame([
            [
                'provider' => 'golf_federation',
                'identifier_type' => 'membership_number',
                'normalized_value' => 'GVF-1001',
                'organization_count' => 2,
                'identifier_count' => 2,
            ],
        ], $results->map(fn ($row) => [
            'provider' => $row->provider,
            'identifier_type' => $row->identifier_type,
            'normalized_value' => $row->normalized_value,
            'organization_count' => (int) $row->organization_count,
            'identifier_count' => (int) $row->identifier_count,
        ])->all());

        $this->assertResultColumns($results, [
            'provider',
            'identifier_type',
            'normalized_value',
            'organization_count',
            'identifier_count',
        ]);
    }
}