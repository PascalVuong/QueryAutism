<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry021OrganizationsWithCustomers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-021';
    }

    public function title(): string
    {
        return 'Organizations with customers';
    }

    public function description(): string
    {
        return 'Return organizations that have at least one customer. '
            .'Order them by organization name.';
    }

    public function concepts(): array
    {
        return [
            'has',
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
        throw new LogicException('QRY-021 has not been solved yet.');
    }
}
