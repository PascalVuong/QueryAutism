# QueryAutism — Phase 2 Blueprint

## Venues, facilities, resources and reservations

Phase Two adds the operational booking layer on top of the identity and
membership data from Phase One.

## Tables

1. `venues`
2. `facilities`
3. `resources`
4. `resource_availability_blocks`
5. `reservations`
6. `reservation_items`
7. `reservation_participants`
8. `reservation_status_histories`

## Relationship map

```text
Organization
├── hasMany Venues
└── hasMany Reservations

Venue
├── belongsTo Organization
├── hasMany Facilities
└── hasMany Reservations

Facility
├── belongsTo Venue
└── hasMany Resources

Resource
├── belongsTo Facility
├── hasMany ResourceAvailabilityBlocks
├── hasMany ReservationItems
└── belongsToMany Reservations through ReservationItems

Customer
├── hasMany Reservations
└── hasMany ReservationParticipants

Reservation
├── belongsTo Organization
├── belongsTo Venue
├── belongsTo Customer
├── hasMany ReservationItems
├── belongsToMany Resources through ReservationItems
├── hasMany ReservationParticipants
├── hasMany ReservationStatusHistories
├── hasOne latestStatusHistory
└── hasOne effectiveStatusHistory
```

## Deterministic query scenarios

The Phase Two scenario seeder includes:

- overlapping bookings for the same resource;
- a reservation during a maintenance block;
- a reservation whose party size exceeds resource capacity;
- completed, cancelled and no-show reservations;
- a reservation without participants;
- a stored reservation status that differs from effective history;
- resources that have never been booked;
- venues belonging to different organizations.

## Planned query coverage

QRY-051 through QRY-075 will cover:

- nested eager loading;
- resources without bookings;
- upcoming and historical reservations;
- time-overlap detection;
- maintenance conflicts;
- capacity violations;
- participant counts and attendance;
- revenue and occupancy reports;
- status-history consistency;
- multi-tenant reporting;
- joins, subqueries, aggregates and PostgreSQL date logic.
