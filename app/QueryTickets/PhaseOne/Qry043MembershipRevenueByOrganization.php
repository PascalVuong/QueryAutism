<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry043MembershipRevenueByOrganization extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-043';
    }

    public function title(): string
    {
        return 'Membership revenue per organization';
    }

    public function description(): string
    {
        return 'Return every organization with the sum of agreed_price from '
            .'its memberships. Organizations without memberships must remain '
            .'in the result. Order by organization name.';
    }

    public function concepts(): array
    {
        return [
            'withSum',
            'aggregate relationship',
            'select',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'memberships_sum_agreed_price',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-043 has not been solved yet.');
    }
}