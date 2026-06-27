# QueryAutism — Phase 3 Blueprint

## Pricing, payments, refunds and credits

Phase Three adds financial data on top of reservations.

## Tables

1. `price_rules`
2. `reservation_charges`
3. `payments`
4. `payment_transactions`
5. `refunds`
6. `credit_accounts`
7. `credit_transactions`

## Relationship map

```text
Organization
├── hasMany PriceRules
├── hasMany Payments
└── hasMany CreditAccounts

Customer
├── hasMany Payments
└── hasMany CreditAccounts

Reservation
├── hasMany ReservationCharges
├── hasMany Payments
└── hasMany Refunds

ReservationItem
└── hasMany ReservationCharges

PriceRule
└── hasMany ReservationCharges

Payment
├── hasMany PaymentTransactions
└── hasMany Refunds

CreditAccount
└── hasMany CreditTransactions
```

## Deterministic scenarios

The Phase Three seeder includes:

- fixed and percentage price rules;
- active, archived and future price rules;
- base charges, surcharges and discounts;
- fully paid reservations;
- an underpaid reservation with a failed remainder;
- failed card payments;
- partial and full refunds;
- immutable payment transaction history;
- active and frozen credit accounts;
- an expired credit account with a positive balance;
- a stored credit balance that differs from transaction history.

## Planned query coverage

QRY-076 through QRY-095 can cover:

- active price rules at a supplied time;
- charge totals and discount calculations;
- paid, unpaid and underpaid reservations;
- failed payment attempts;
- partial and full refunds;
- net revenue;
- payment reconciliation;
- customer payment history;
- credit balances and credit usage;
- balance mismatch detection;
- running balances with window functions;
- financial reports per organization and venue.
