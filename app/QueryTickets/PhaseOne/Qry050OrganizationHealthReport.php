<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry050OrganizationHealthReport extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-050';
    }

    public function title(): string
    {
        return 'Organization health report';
    }

    public function description(): string
    {
        return 'Return one row per organization with customer_count, '
            .'active_membership_count, expired_membership_count, '
            .'membership_revenue, blocked_customer_count and '
            .'latest_membership_updated_at. Numeric metrics must be zero '
            .'when no related rows exist. Order by organization name.';
    }

    public function concepts(): array
    {
        return [
            'conditional aggregates',
            'correlated subqueries',
            'coalesce',
            'report query',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'customer_count',
            'active_membership_count',
            'expired_membership_count',
            'membership_revenue',
            'blocked_customer_count',
            'latest_membership_updated_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-050 has not been solved yet.');
    }
}