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
use App\QueryTickets\PhaseOne\Qry031CurrentActiveMemberships;
use App\QueryTickets\PhaseOne\Qry032ExpiredMembershipsByEndDate;
use App\QueryTickets\PhaseOne\Qry033UpcomingMemberships;
use App\QueryTickets\PhaseOne\Qry034AutoRenewingMemberships;
use App\QueryTickets\PhaseOne\Qry035CancelledMembershipsWithReason;
use App\QueryTickets\PhaseOne\Qry036MembershipStatusTimeline;
use App\QueryTickets\PhaseOne\Qry037MembershipsWithLatestStatus;
use App\QueryTickets\PhaseOne\Qry038MembershipStatusMismatches;
use App\QueryTickets\PhaseOne\Qry039StaleCustomerProfiles;
use App\QueryTickets\PhaseOne\Qry040OverlappingMemberships;
use App\QueryTickets\PhaseOne\Qry041MembershipCountByStatus;
use App\QueryTickets\PhaseOne\Qry042AveragePlanPriceByOrganization;
use App\QueryTickets\PhaseOne\Qry043MembershipRevenueByOrganization;
use App\QueryTickets\PhaseOne\Qry044MostExpensivePlanPerOrganization;
use App\QueryTickets\PhaseOne\Qry045OrganizationWithMostCustomers;
use App\QueryTickets\PhaseOne\Qry046CustomerWithMostMemberships;
use App\QueryTickets\PhaseOne\Qry047DuplicateExternalIdentifiers;
use App\QueryTickets\PhaseOne\Qry048CrossOrganizationVerifiedIdentifiers;
use App\QueryTickets\PhaseOne\Qry049CustomerMembershipReport;
use App\QueryTickets\PhaseOne\Qry050OrganizationHealthReport;
use App\QueryTickets\PhaseTwo\Qry051ActiveVenues;
use App\QueryTickets\PhaseTwo\Qry052FacilitiesForGreenValleyVenue;
use App\QueryTickets\PhaseTwo\Qry053BookableActiveResources;
use App\QueryTickets\PhaseTwo\Qry054MaintenanceResources;
use App\QueryTickets\PhaseTwo\Qry055UpcomingReservations;
use App\QueryTickets\PhaseTwo\Qry056ReservationsWithCustomerAndVenue;
use App\QueryTickets\PhaseTwo\Qry057ReservationsWithoutParticipants;
use App\QueryTickets\PhaseTwo\Qry058ReservationsWithParticipantCount;
use App\QueryTickets\PhaseTwo\Qry059ResourcesNeverBooked;
use App\QueryTickets\PhaseTwo\Qry060ReservationsWithMultipleResources;
use App\QueryTickets\PhaseTwo\Qry061OverlappingResourceReservations;
use App\QueryTickets\PhaseTwo\Qry062ReservationsDuringAvailabilityBlocks;
use App\QueryTickets\PhaseTwo\Qry063ReservationsExceedingResourceCapacity;
use App\QueryTickets\PhaseTwo\Qry064ReservationParticipantCountMismatches;
use App\QueryTickets\PhaseTwo\Qry065ReservationsWithoutCreator;
use App\QueryTickets\PhaseTwo\Qry066CustomersWithUpcomingReservations;
use App\QueryTickets\PhaseTwo\Qry067VenuesWithReservationCount;
use App\QueryTickets\PhaseTwo\Qry068ResourcesWithUpcomingReservationCount;
use App\QueryTickets\PhaseTwo\Qry069ReservationItemsWithResourceLocation;
use App\QueryTickets\PhaseTwo\Qry070ReservationsWithCheckedInParticipants;
use App\QueryTickets\PhaseTwo\Qry071ReservationStatusMismatches;
use App\QueryTickets\PhaseTwo\Qry072ReservationStatusTimeline;
use App\QueryTickets\PhaseTwo\Qry073VenueReservationRevenue;
use App\QueryTickets\PhaseTwo\Qry074ResourcesWithNonCancelledBookingCount;
use App\QueryTickets\PhaseTwo\Qry075OrganizationReservationHealthReport;
use App\QueryTickets\PhaseThree\Qry076CurrentlyActivePriceRules;
use App\QueryTickets\PhaseThree\Qry077ReservationChargeTotals;
use App\QueryTickets\PhaseThree\Qry078ReservationsWithoutPayments;
use App\QueryTickets\PhaseThree\Qry079UnderpaidReservations;
use App\QueryTickets\PhaseThree\Qry080FailedPayments;
use App\QueryTickets\PhaseThree\Qry081PartiallyRefundedPayments;
use App\QueryTickets\PhaseThree\Qry082FullyRefundedPayments;
use App\QueryTickets\PhaseThree\Qry083NetPaidAmountPerReservation;
use App\QueryTickets\PhaseThree\Qry084CustomersWithCreditAccounts;
use App\QueryTickets\PhaseThree\Qry085ExpiredCreditAccountsWithBalance;
use App\QueryTickets\PhaseThree\Qry086CreditBalanceMismatches;
use App\QueryTickets\PhaseThree\Qry087CreditTransactionRunningBalances;
use App\QueryTickets\PhaseThree\Qry088PaymentTransactionTimeline;
use App\QueryTickets\PhaseThree\Qry089PaymentsWithMultipleTransactions;
use App\QueryTickets\PhaseThree\Qry090RefundReconciliation;
use App\QueryTickets\PhaseThree\Qry091CustomerPaymentSummary;
use App\QueryTickets\PhaseThree\Qry092OrganizationNetRevenue;
use App\QueryTickets\PhaseThree\Qry093VenueNetRevenue;
use App\QueryTickets\PhaseThree\Qry094PaymentMethodBreakdown;
use App\QueryTickets\PhaseThree\Qry095FinancialHealthReport;
use App\QueryTickets\PhaseFour\Qry096ActiveProductsWithCategory;
use App\QueryTickets\PhaseFour\Qry097ProductsWithoutVariants;
use App\QueryTickets\PhaseFour\Qry098StockTrackedVariantsWithoutInventory;
use App\QueryTickets\PhaseFour\Qry099AvailableStockPerLocation;
use App\QueryTickets\PhaseFour\Qry100LowStockInventoryLevels;
use App\QueryTickets\PhaseFour\Qry101OutOfStockVariants;
use App\QueryTickets\PhaseFour\Qry102SalesOrdersWithoutItems;
use App\QueryTickets\PhaseFour\Qry103SalesOrdersWithItemCount;
use App\QueryTickets\PhaseFour\Qry104CustomersWithMultipleSalesOrders;
use App\QueryTickets\PhaseFour\Qry105SalesOrderTotalMismatches;
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
        Qry031CurrentActiveMemberships::class,
        Qry032ExpiredMembershipsByEndDate::class,
        Qry033UpcomingMemberships::class,
        Qry034AutoRenewingMemberships::class,
        Qry035CancelledMembershipsWithReason::class,
        Qry036MembershipStatusTimeline::class,
        Qry037MembershipsWithLatestStatus::class,
        Qry038MembershipStatusMismatches::class,
        Qry039StaleCustomerProfiles::class,
        Qry040OverlappingMemberships::class,
        Qry041MembershipCountByStatus::class,
        Qry042AveragePlanPriceByOrganization::class,
        Qry043MembershipRevenueByOrganization::class,
        Qry044MostExpensivePlanPerOrganization::class,
        Qry045OrganizationWithMostCustomers::class,
        Qry046CustomerWithMostMemberships::class,
        Qry047DuplicateExternalIdentifiers::class,
        Qry048CrossOrganizationVerifiedIdentifiers::class,
        Qry049CustomerMembershipReport::class,
        Qry050OrganizationHealthReport::class,
        Qry051ActiveVenues::class,
        Qry052FacilitiesForGreenValleyVenue::class,
        Qry053BookableActiveResources::class,
        Qry054MaintenanceResources::class,
        Qry055UpcomingReservations::class,
        Qry056ReservationsWithCustomerAndVenue::class,
        Qry057ReservationsWithoutParticipants::class,
        Qry058ReservationsWithParticipantCount::class,
        Qry059ResourcesNeverBooked::class,
        Qry060ReservationsWithMultipleResources::class,
        Qry061OverlappingResourceReservations::class,
        Qry062ReservationsDuringAvailabilityBlocks::class,
        Qry063ReservationsExceedingResourceCapacity::class,
        Qry064ReservationParticipantCountMismatches::class,
        Qry065ReservationsWithoutCreator::class,
        Qry066CustomersWithUpcomingReservations::class,
        Qry067VenuesWithReservationCount::class,
        Qry068ResourcesWithUpcomingReservationCount::class,
        Qry069ReservationItemsWithResourceLocation::class,
        Qry070ReservationsWithCheckedInParticipants::class,
        Qry071ReservationStatusMismatches::class,
        Qry072ReservationStatusTimeline::class,
        Qry073VenueReservationRevenue::class,
        Qry074ResourcesWithNonCancelledBookingCount::class,
        Qry075OrganizationReservationHealthReport::class,
        Qry076CurrentlyActivePriceRules::class,
        Qry077ReservationChargeTotals::class,
        Qry078ReservationsWithoutPayments::class,
        Qry079UnderpaidReservations::class,
        Qry080FailedPayments::class,
        Qry081PartiallyRefundedPayments::class,
        Qry082FullyRefundedPayments::class,
        Qry083NetPaidAmountPerReservation::class,
        Qry084CustomersWithCreditAccounts::class,
        Qry085ExpiredCreditAccountsWithBalance::class,
        Qry086CreditBalanceMismatches::class,
        Qry087CreditTransactionRunningBalances::class,
        Qry088PaymentTransactionTimeline::class,
        Qry089PaymentsWithMultipleTransactions::class,
        Qry090RefundReconciliation::class,
        Qry091CustomerPaymentSummary::class,
        Qry092OrganizationNetRevenue::class,
        Qry093VenueNetRevenue::class,
        Qry094PaymentMethodBreakdown::class,
        Qry095FinancialHealthReport::class,
        Qry096ActiveProductsWithCategory::class,
        Qry097ProductsWithoutVariants::class,
        Qry098StockTrackedVariantsWithoutInventory::class,
        Qry099AvailableStockPerLocation::class,
        Qry100LowStockInventoryLevels::class,
        Qry101OutOfStockVariants::class,
        Qry102SalesOrdersWithoutItems::class,
        Qry103SalesOrdersWithItemCount::class,
        Qry104CustomersWithMultipleSalesOrders::class,
        Qry105SalesOrderTotalMismatches::class,
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