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
use App\QueryTickets\PhaseOne\Qry011UsersWithProfiles;
use App\QueryTickets\PhaseOne\Qry012UsersWithoutProfiles;
use App\QueryTickets\PhaseOne\Qry013CustomersWithoutAccounts;
use App\QueryTickets\PhaseOne\Qry014CustomersWithUserAccounts;
use App\QueryTickets\PhaseOne\Qry015CustomersWithProfiles;
use App\QueryTickets\PhaseOne\Qry016ChildOrganizationsWithParent;
use App\QueryTickets\PhaseOne\Qry017ParentOrganizationsWithChildren;
use App\QueryTickets\PhaseOne\Qry018UsersWithOrganizations;
use App\QueryTickets\PhaseOne\Qry019UsersInMultipleOrganizations;
use App\QueryTickets\PhaseOne\Qry020MembershipsWithCustomerAndPlan;
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
        Qry011UsersWithProfiles::class,
        Qry012UsersWithoutProfiles::class,
        Qry013CustomersWithoutAccounts::class,
        Qry014CustomersWithUserAccounts::class,
        Qry015CustomersWithProfiles::class,
        Qry016ChildOrganizationsWithParent::class,
        Qry017ParentOrganizationsWithChildren::class,
        Qry018UsersWithOrganizations::class,
        Qry019UsersInMultipleOrganizations::class,
        Qry020MembershipsWithCustomerAndPlan::class,
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