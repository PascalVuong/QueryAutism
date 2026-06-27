<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry007ActiveOrPendingUsers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-007';
    }

    public function title(): string
    {
        return 'Active or pending users';
    }

    public function description(): string
    {
        return 'Return users whose status is active or pending. Order first by status and then by email address.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'whereIn',
            'multiple orderBy calls',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-007 has not been solved yet.');
    }
}