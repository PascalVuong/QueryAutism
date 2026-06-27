<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry046CustomerWithMostMemberships extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-046';
    }

    public function title(): string
    {
        return 'Customer with most memberships';
    }

    public function description(): string
    {
        return 'Return only the customer with the highest number of '
            .'memberships. Include memberships_count and select only the '
            .'requested columns.';
    }

    public function concepts(): array
    {
        return [
            'withCount',
            'orderByDesc',
            'limit',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'customer_number',
            'first_name',
            'last_name',
            'memberships_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-046 has not been solved yet.');
    }
}