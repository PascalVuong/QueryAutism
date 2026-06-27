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
use App\QueryTickets\PhaseOne\Qry021OrganizationsWithCustomers;
use App\QueryTickets\PhaseOne\Qry022OrganizationsWithoutCustomers;
use App\QueryTickets\PhaseOne\Qry023CustomersWithoutMemberships;
use App\QueryTickets\PhaseOne\Qry024CustomersWithActiveMemberships;
use App\QueryTickets\PhaseOne\Qry025CustomersWithoutActiveMemberships;
use App\QueryTickets\PhaseOne\Qry026CustomersWithMultipleMemberships;
use App\QueryTickets\PhaseOne\Qry027OrganizationsWithCustomerCount;
use App\QueryTickets\PhaseOne\Qry028CustomersWithMembershipCount;
use App\QueryTickets\PhaseOne\Qry029PlansWithActiveMembershipCount;
use App\QueryTickets\PhaseOne\Qry030HighRiskCustomers;
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
        Qry021OrganizationsWithCustomers::class,
        Qry022OrganizationsWithoutCustomers::class,
        Qry023CustomersWithoutMemberships::class,
        Qry024CustomersWithActiveMemberships::class,
        Qry025CustomersWithoutActiveMemberships::class,
        Qry026CustomersWithMultipleMemberships::class,
        Qry027OrganizationsWithCustomerCount::class,
        Qry028CustomersWithMembershipCount::class,
        Qry029PlansWithActiveMembershipCount::class,
        Qry030HighRiskCustomers::class,
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