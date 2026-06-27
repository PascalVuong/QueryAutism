<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry044MostExpensivePlanPerOrganization extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-044';
    }

    public function title(): string
    {
        return 'Most expensive plan per organization';
    }

    public function description(): string
    {
        return 'Return the most expensive membership plan for each '
            .'organization that has plans. Select only the requested plan '
            .'columns and order by organization_id.';
    }

    public function concepts(): array
    {
        return [
            'correlated subquery',
            'max',
            'whereColumn',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'code',
            'name',
            'price',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-044 has not been solved yet.');
    }
}