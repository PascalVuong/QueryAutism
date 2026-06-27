<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry027OrganizationsWithCustomerCount extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-027';
    }

    public function title(): string
    {
        return 'Organizations with customer count';
    }

    public function description(): string
    {
        return 'Return every organization with customers_count. Order by '
            .'customers_count descending and then by name ascending.';
    }

    public function concepts(): array
    {
        return [
            'withCount',
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
            'name',
            'slug',
            'customers_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-027 has not been solved yet.');
    }
}
