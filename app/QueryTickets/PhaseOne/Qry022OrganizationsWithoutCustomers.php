<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry022OrganizationsWithoutCustomers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-022';
    }

    public function title(): string
    {
        return 'Organizations without customers';
    }

    public function description(): string
    {
        return 'Return organizations that do not have any customers. '
            .'Order them by organization name.';
    }

    public function concepts(): array
    {
        return [
            'doesntHave',
            'select',
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
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-022 has not been solved yet.');
    }
}
