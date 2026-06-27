<?php

namespace App\QueryTickets;

use App\QueryTickets\PhaseOne\Qry001ActiveUsers;
use App\QueryTickets\PhaseOne\Qry002UnverifiedUsers;
use App\QueryTickets\PhaseOne\Qry003UsersWhoNeverLoggedIn;
use App\QueryTickets\PhaseOne\Qry004SuspendedUsers;
use App\QueryTickets\PhaseOne\Qry005RecentLogins;
use App\QueryTickets\PhaseOne\Qry006DutchLocaleUsers;
use App\QueryTickets\PhaseOne\Qry007ActiveOrPendingUsers;
use App\QueryTickets\PhaseOne\Qry008GuestCustomers;
use App\QueryTickets\PhaseOne\Qry009CustomersWithMarketingConsent;
use App\QueryTickets\PhaseOne\Qry010ActiveMembershipPlansByPrice;
use Illuminate\Support\Collection;

class QueryTicketRegistry
{
    /**
     * @var array<int, class-string<QueryTicket>>
     */
    private const TICKETS = [
        Qry001ActiveUsers::class,
        Qry002UnverifiedUsers::class,
        Qry003UsersWhoNeverLoggedIn::class,
        Qry004SuspendedUsers::class,
        Qry005RecentLogins::class,
        Qry006DutchLocaleUsers::class,
        Qry007ActiveOrPendingUsers::class,
        Qry008GuestCustomers::class,
        Qry009CustomersWithMarketingConsent::class,
        Qry010ActiveMembershipPlansByPrice::class,
    ];

    /**
     * @return Collection<int, QueryTicket>
     */
    public function all(): Collection
    {
        return collect(self::TICKETS)
            ->map(fn (string $ticketClass) => app($ticketClass));
    }

    public function find(string $ticketId): ?QueryTicket
    {
        return $this->all()->first(
            fn (QueryTicket $ticket) => $ticket->id() === strtoupper($ticketId),
        );
    }
}