<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Facility;
use App\Models\Organization;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationParticipant;
use App\Models\ReservationStatusHistory;
use App\Models\Resource;
use App\Models\ResourceAvailabilityBlock;
use App\Models\User;
use App\Models\Venue;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class PhaseTwoScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $now = CarbonImmutable::now()->startOfMinute();

        $group = Organization::query()
            ->where('slug', 'venueops-leisure-group')
            ->firstOrFail();

        $greenValley = Organization::query()
            ->where('slug', 'green-valley-golf-club')
            ->firstOrFail();

        $rotterdamPadel = Organization::query()
            ->where('slug', 'rotterdam-padel-centre')
            ->firstOrFail();

        $serenityWellness = Organization::query()
            ->where('slug', 'serenity-wellness')
            ->firstOrFail();

        $owner = User::query()
            ->where('email', 'owner@queryautism.test')
            ->firstOrFail();

        $multiManager = User::query()
            ->where('email', 'multi.manager@queryautism.test')
            ->firstOrFail();

        $pascalMember = $this->customer('GV-0001');
        $greenGuest = $this->customer('GV-0002');
        $blockedCustomer = $this->customer('GV-0003');
        $multiGreen = $this->customer('GV-0004');
        $multiRotterdam = $this->customer('RP-0001');
        $noMembershipCustomer = $this->customer('RP-0002');

        $serenityCustomer = Customer::factory()
            ->for($serenityWellness)
            ->guest()
            ->create([
                'customer_number' => 'SW-0001',
                'first_name' => 'Nina',
                'last_name' => 'No Show',
                'email' => 'nina.noshow@example.test',
            ]);

        $groupVenue = Venue::factory()
            ->for($group)
            ->active()
            ->create([
                'name' => 'VenueOps Training Centre',
                'slug' => 'venueops-training-centre',
                'city' => 'Rotterdam',
            ]);

        $greenVenue = Venue::factory()
            ->for($greenValley)
            ->active()
            ->create([
                'name' => 'Green Valley Main Venue',
                'slug' => 'green-valley-main-venue',
                'city' => 'Schiedam',
            ]);

        $rotterdamVenue = Venue::factory()
            ->for($rotterdamPadel)
            ->active()
            ->create([
                'name' => 'Rotterdam Padel Hall',
                'slug' => 'rotterdam-padel-hall',
                'city' => 'Rotterdam',
            ]);

        $serenityVenue = Venue::factory()
            ->for($serenityWellness)
            ->active()
            ->create([
                'name' => 'Serenity Spa',
                'slug' => 'serenity-spa',
                'city' => 'Delft',
            ]);

        $meetingRooms = $this->facility(
            $groupVenue,
            'MEETING',
            'Meeting Rooms',
            'meeting',
        );

        $golfCourse = $this->facility(
            $greenVenue,
            'COURSE',
            'Golf Course',
            'course',
        );

        $clubhouse = $this->facility(
            $greenVenue,
            'CLUB',
            'Clubhouse',
            'hospitality',
        );

        $padelCourts = $this->facility(
            $rotterdamVenue,
            'COURTS',
            'Padel Courts',
            'court',
        );

        $padelLounge = $this->facility(
            $rotterdamVenue,
            'LOUNGE',
            'Padel Lounge',
            'hospitality',
        );

        $treatmentRooms = $this->facility(
            $serenityVenue,
            'TREATMENT',
            'Treatment Rooms',
            'wellness',
        );

        $boardroom = $this->resource(
            $meetingRooms,
            'BOARDROOM',
            'Boardroom',
            'room',
            12,
        );

        $northCourse = $this->resource(
            $golfCourse,
            'NORTH',
            'North Course',
            'course',
            4,
        );

        $simulator = $this->resource(
            $clubhouse,
            'SIM-1',
            'Simulator 1',
            'simulator',
            4,
        );

        $tableA = $this->resource(
            $clubhouse,
            'TABLE-A',
            'Restaurant Table A',
            'table',
            6,
        );

        $courtOne = $this->resource(
            $padelCourts,
            'COURT-1',
            'Padel Court 1',
            'court',
            4,
        );

        $courtTwo = $this->resource(
            $padelCourts,
            'COURT-2',
            'Padel Court 2',
            'court',
            4,
        );

        $courtThree = Resource::factory()
            ->for($padelCourts)
            ->maintenance()
            ->withCapacity(4)
            ->create([
                'code' => 'COURT-3',
                'name' => 'Padel Court 3',
                'type' => 'court',
            ]);

        $loungeTable = $this->resource(
            $padelLounge,
            'LOUNGE-TABLE',
            'Lounge Table',
            'table',
            8,
        );

        $treatmentRoom = $this->resource(
            $treatmentRooms,
            'ROOM-1',
            'Treatment Room 1',
            'room',
            2,
        );

        ResourceAvailabilityBlock::factory()
            ->forResource($courtTwo)
            ->maintenance()
            ->create([
                'starts_at' => $now->addDay()->setTime(10, 0),
                'ends_at' => $now->addDay()->setTime(12, 0),
                'reason' => 'Scheduled court maintenance',
                'created_by_user_id' => $multiManager->id,
            ]);

        ResourceAvailabilityBlock::factory()
            ->forResource($northCourse)
            ->create([
                'starts_at' => $now->addDays(3)->setTime(15, 0),
                'ends_at' => $now->addDays(3)->setTime(17, 0),
                'reason' => 'Private tournament preparation',
                'created_by_user_id' => $owner->id,
            ]);

        $overlapOne = $this->reservation(
            $pascalMember,
            $greenVenue,
            'GV-RES-0001',
            'confirmed',
            $now->addDay()->setTime(9, 0),
            $now->addDay()->setTime(10, 0),
            4,
            120,
            $owner,
        );
        $this->item($overlapOne, $northCourse, 120);
        $this->participants($overlapOne, $pascalMember, 4);
        $this->pendingAndConfirmedHistory($overlapOne, $owner, $now);

        $overlapTwo = $this->reservation(
            $greenGuest,
            $greenVenue,
            'GV-RES-0002',
            'confirmed',
            $now->addDay()->setTime(9, 30),
            $now->addDay()->setTime(10, 30),
            2,
            60,
            $owner,
        );
        $this->item($overlapTwo, $northCourse, 60);
        $this->participants($overlapTwo, $greenGuest, 2);
        $this->history(
            $overlapTwo,
            null,
            'confirmed',
            $now->subHours(8),
            $owner,
        );

        $completed = $this->reservation(
            $multiRotterdam,
            $rotterdamVenue,
            'RP-RES-0001',
            'completed',
            $now->subDay()->setTime(18, 0),
            $now->subDay()->setTime(19, 0),
            4,
            80,
            $multiManager,
        );
        $this->item($completed, $courtOne, 80, 'completed');
        $this->participants(
            $completed,
            $multiRotterdam,
            4,
            'attended',
        );
        $this->history(
            $completed,
            null,
            'confirmed',
            $now->subDays(2),
            $multiManager,
        );
        $this->history(
            $completed,
            'confirmed',
            'completed',
            $now->subDay()->setTime(19, 0),
            $multiManager,
        );

        $cancelled = $this->reservation(
            $blockedCustomer,
            $greenVenue,
            'GV-RES-0003',
            'cancelled',
            $now->addDay()->setTime(19, 0),
            $now->addDay()->setTime(20, 0),
            6,
            150,
            $owner,
            [
                'cancelled_at' => $now->subHour(),
                'cancellation_reason' => 'Customer requested cancellation',
            ],
        );
        $this->item($cancelled, $tableA, 150, 'cancelled');
        $this->participants($cancelled, $blockedCustomer, 1, 'cancelled');
        $this->history(
            $cancelled,
            null,
            'confirmed',
            $now->subDay(),
            $owner,
        );
        $this->history(
            $cancelled,
            'confirmed',
            'cancelled',
            $now->subHour(),
            $owner,
        );

        $blockedByMaintenance = $this->reservation(
            $noMembershipCustomer,
            $rotterdamVenue,
            'RP-RES-0002',
            'confirmed',
            $now->addDay()->setTime(10, 30),
            $now->addDay()->setTime(11, 30),
            4,
            80,
            $multiManager,
        );
        $this->item($blockedByMaintenance, $courtTwo, 80);
        $this->participants(
            $blockedByMaintenance,
            $noMembershipCustomer,
            4,
        );
        $this->history(
            $blockedByMaintenance,
            null,
            'confirmed',
            $now->subHours(4),
            $multiManager,
        );

        $noShow = $this->reservation(
            $serenityCustomer,
            $serenityVenue,
            'SW-RES-0001',
            'no_show',
            $now->subDay()->setTime(14, 0),
            $now->subDay()->setTime(15, 0),
            1,
            90,
            null,
        );
        $this->item($noShow, $treatmentRoom, 90, 'completed');
        $this->participants($noShow, $serenityCustomer, 1, 'no_show');
        $this->history(
            $noShow,
            null,
            'confirmed',
            $now->subDays(2),
        );
        $this->history(
            $noShow,
            'confirmed',
            'no_show',
            $now->subDay()->setTime(15, 0),
        );

        $overCapacity = $this->reservation(
            $multiGreen,
            $greenVenue,
            'GV-RES-0004',
            'confirmed',
            $now->addDays(2)->setTime(11, 0),
            $now->addDays(2)->setTime(12, 0),
            5,
            100,
            $owner,
        );
        $this->item($overCapacity, $simulator, 100);
        $this->participants($overCapacity, $multiGreen, 5);
        $this->history(
            $overCapacity,
            null,
            'confirmed',
            $now->subHours(2),
            $owner,
        );

        $statusMismatch = $this->reservation(
            $multiRotterdam,
            $rotterdamVenue,
            'RP-RES-0003',
            'pending',
            $now->addDays(4)->setTime(20, 0),
            $now->addDays(4)->setTime(21, 0),
            3,
            0,
            $multiManager,
        );
        $this->item($statusMismatch, $loungeTable, 0);
        $this->history(
            $statusMismatch,
            null,
            'pending',
            $now->subDays(2),
            $multiManager,
        );
        $this->history(
            $statusMismatch,
            'pending',
            'confirmed',
            $now->subHour(),
            $multiManager,
        );

        unset(
            $boardroom,
            $courtThree,
        );
    }

    private function customer(string $customerNumber): Customer
    {
        return Customer::query()
            ->where('customer_number', $customerNumber)
            ->firstOrFail();
    }

    private function facility(
        Venue $venue,
        string $code,
        string $name,
        string $type,
    ): Facility {
        return Facility::factory()
            ->for($venue)
            ->active()
            ->create([
                'code' => $code,
                'name' => $name,
                'type' => $type,
            ]);
    }

    private function resource(
        Facility $facility,
        string $code,
        string $name,
        string $type,
        int $capacity,
    ): Resource {
        return Resource::factory()
            ->for($facility)
            ->active()
            ->withCapacity($capacity)
            ->create([
                'code' => $code,
                'name' => $name,
                'type' => $type,
            ]);
    }

    /**
     * @param array<string, mixed> $extra
     */
    private function reservation(
        Customer $customer,
        Venue $venue,
        string $referenceNumber,
        string $status,
        CarbonImmutable $startsAt,
        CarbonImmutable $endsAt,
        int $partySize,
        float|int $total,
        ?User $createdBy,
        array $extra = [],
    ): Reservation {
        return Reservation::factory()
            ->forCustomerAtVenue($customer, $venue)
            ->create(array_merge([
                'reference_number' => $referenceNumber,
                'status' => $status,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'party_size' => $partySize,
                'subtotal' => $total,
                'discount_total' => 0,
                'total' => $total,
                'created_by_user_id' => $createdBy?->id,
            ], $extra));
    }

    private function item(
        Reservation $reservation,
        Resource $resource,
        float|int $price,
        string $status = 'reserved',
    ): ReservationItem {
        return ReservationItem::factory()
            ->forReservationAndResource($reservation, $resource)
            ->create([
                'unit_price' => $price,
                'total_price' => $price,
                'status' => $status,
            ]);
    }

    private function participants(
        Reservation $reservation,
        Customer $booker,
        int $amount,
        string $status = 'registered',
    ): void {
        ReservationParticipant::factory()
            ->forReservation($reservation)
            ->forCustomer($booker)
            ->booker()
            ->create([
                'status' => $status,
                'checked_in_at' => $status === 'attended'
                    ? $reservation->starts_at
                    : null,
            ]);

        if ($amount <= 1) {
            return;
        }

        ReservationParticipant::factory()
            ->count($amount - 1)
            ->forReservation($reservation)
            ->create([
                'status' => $status,
                'checked_in_at' => $status === 'attended'
                    ? $reservation->starts_at
                    : null,
            ]);
    }

    private function pendingAndConfirmedHistory(
        Reservation $reservation,
        User $changedBy,
        CarbonImmutable $now,
    ): void {
        $this->history(
            $reservation,
            null,
            'pending',
            $now->subDay(),
            $changedBy,
        );

        $this->history(
            $reservation,
            'pending',
            'confirmed',
            $now->subHours(12),
            $changedBy,
        );
    }

    private function history(
        Reservation $reservation,
        ?string $fromStatus,
        string $toStatus,
        CarbonImmutable $effectiveAt,
        ?User $changedBy = null,
    ): ReservationStatusHistory {
        return ReservationStatusHistory::factory()
            ->for($reservation)
            ->create([
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'changed_by_user_id' => $changedBy?->id,
                'effective_at' => $effectiveAt,
            ]);
    }
}
