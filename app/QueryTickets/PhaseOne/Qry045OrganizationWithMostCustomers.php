<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry045OrganizationWithMostCustomers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-045';
    }

    public function title(): string
    {
        return 'Organization with most customers';
    }

    public function description(): string
    {
        return 'Return only the organization with the highest number of '
            .'customers. Include customers_count and select only the '
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
            'name',
            'customers_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-045 has not been solved yet.');
    }
}