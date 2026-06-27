<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry010ActiveMembershipPlansByPrice extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-010';
    }

    public function title(): string
    {
        return 'Active membership plans by price';
    }

    public function description(): string
    {
        return 'Return active membership plans from cheapest to most expensive. Use the plan name as the secondary sort.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'where',
            'multiple orderBy calls',
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
            'price',
            'currency',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-010 has not been solved yet.');
    }
}