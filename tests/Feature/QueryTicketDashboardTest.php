<?php

namespace Tests\Feature;

use Tests\TestCase;

class QueryTicketDashboardTest extends TestCase
{
    public function test_home_redirects_to_query_ticket_dashboard(): void
    {
        $this->get('/')
            ->assertRedirect('/queries');
    }

    public function test_query_ticket_dashboard_lists_available_tickets(): void
    {
        $this->get('/queries')
            ->assertSuccessful()
            ->assertSee('Query tickets')
            ->assertSee('QRY-001')
            ->assertSee('Active users ordered by latest login');
    }

    public function test_unsolved_ticket_page_is_accessible(): void
    {
        $this->get('/queries/QRY-001')
            ->assertSuccessful()
            ->assertSee('QRY-001')
            ->assertSee('QRY-001 has not been solved yet.')
            ->assertSee('app/QueryTickets/PhaseOne/Qry001ActiveUsers.php');
    }

    public function test_unknown_ticket_returns_not_found(): void
    {
        $this->get('/queries/QRY-999')
            ->assertNotFound();
    }
}
