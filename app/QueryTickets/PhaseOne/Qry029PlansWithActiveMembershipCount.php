<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry029PlansWithActiveMembershipCount extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-029';
    }

    public function title(): string
    {
        return 'Plans with active membership count';
    }

    public function description(): string
    {
        return 'Return every membership plan with active_memberships_count, '
            .'counting only memberships whose status is active. Order by the '
            .'count descending and then by plan name.';
    }

    public function concepts(): array
    {
        return [
            'constrained withCount',
            'count alias',
            'select',
            'orderByDesc',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'code',
            'name',
            'status',
            'active_memberships_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-029 has not been solved yet.');
    }
}
