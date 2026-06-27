<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QueryTickets\PhaseOne\Qry009CustomersWithMarketingConsent;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

class Qry009CustomersWithMarketingConsentTest extends QueryTicketTestCase
{
    public function test_it_returns_customers_with_marketing_consent(): void
    {
        $results = $this->runTicket(
            Qry009CustomersWithMarketingConsent::class,
        );

        $this->assertSame([
            'GV-0001',
            'GV-0002',
            'GV-0004',
        ], $results->pluck('customer_number')->all());

        $this->assertTrue(
            $results->every(
                fn ($customer) => $customer->marketing_consent,
            ),
        );

        $this->assertExactColumns($results, [
            'id',
            'organization_id',
            'customer_number',
            'email',
            'marketing_consent',
        ]);
    }
}