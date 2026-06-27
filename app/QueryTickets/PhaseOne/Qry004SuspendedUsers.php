<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry004SuspendedUsers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-004';
    }

    public function title(): string
    {
        return 'Suspended users';
    }

    public function description(): string
    {
        return 'Return all suspended users ordered by email address.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'where',
            'orderBy',
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
            'last_login_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-004 has not been solved yet.');
    }
}