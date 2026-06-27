<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry012UsersWithoutProfiles extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-012';
    }

    public function title(): string
    {
        return 'Users without profiles';
    }

    public function description(): string
    {
        return 'Return users who do not have a profile. '
            .'Order them by email address.';
    }

    public function concepts(): array
    {
        return [
            'doesntHave',
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
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-012 has not been solved yet.');
    }
}
