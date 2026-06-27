<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry011UsersWithProfiles extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-011';
    }

    public function title(): string
    {
        return 'Users with profiles';
    }

    public function description(): string
    {
        return 'Return users who have a profile. Eager load the profile '
            .'and order the users by email address.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'with',
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
        throw new LogicException('QRY-011 has not been solved yet.');
    }
}
