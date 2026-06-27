<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry005RecentLogins extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-005';
    }

    public function title(): string
    {
        return 'Recent logins';
    }

    public function description(): string
    {
        return 'Return users who logged in during the last seven days. Show the most recent login first.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'where',
            'now',
            'orderByDesc',
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
        throw new LogicException('QRY-005 has not been solved yet.');
    }
}