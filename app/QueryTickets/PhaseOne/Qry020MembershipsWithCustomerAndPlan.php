<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry020MembershipsWithCustomerAndPlan extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-020';
    }

    public function title(): string
    {
        return 'Memberships with customer and plan';
    }

    public function description(): string
    {
        return 'Return every membership with its customer and membership '
            .'plan eager loaded. Select only the requested relation '
            .'columns and order memberships by id.';
    }

    public function concepts(): array
    {
        return [
            'multiple eager loads',
            'belongsTo relations',
            'constrained eager loading',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'customer_id',
            'membership_plan_id',
            'status',
            'starts_at',
            'ends_at',
            'agreed_price',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-020 has not been solved yet.');
    }
}
