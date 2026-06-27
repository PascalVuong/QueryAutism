<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry034AutoRenewingMemberships extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-034';
    }

    public function title(): string
    {
        return 'Active auto-renewing memberships';
    }

    public function description(): string
    {
        return 'Return active memberships for which automatic renewal is '
            .'enabled. Order by membership number.';
    }

    public function concepts(): array
    {
        return [
            'multiple where clauses',
            'boolean filter',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'membership_number',
            'status',
            'auto_renew',
            'ends_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-034 has not been solved yet.');
    }
}
