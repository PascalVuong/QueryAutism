<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry049CustomerMembershipReport extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-049';
    }

    public function title(): string
    {
        return 'Customer membership report';
    }

    public function description(): string
    {
        return 'Build a report by joining memberships, customers, '
            .'organizations and membership plans. Return the requested '
            .'aliases and order by organization name, customer number and '
            .'membership number.';
    }

    public function concepts(): array
    {
        return [
            'join',
            'column aliases',
            'report query',
            'multiple orderBy calls',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'organization_name',
            'customer_number',
            'first_name',
            'last_name',
            'membership_number',
            'plan_name',
            'membership_status',
            'agreed_price',
            'ends_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-049 has not been solved yet.');
    }
}