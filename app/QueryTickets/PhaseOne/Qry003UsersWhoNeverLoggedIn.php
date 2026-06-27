<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry003UsersWhoNeverLoggedIn extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-003';
    }

    public function title(): string
    {
        return 'Users who never logged in';
    }

    public function description(): string
    {
        return 'Return users without a last login timestamp. Order them by name.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'whereNull',
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
            'last_login_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-003 has not been solved yet.');
    }
}