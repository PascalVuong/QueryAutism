<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry019UsersInMultipleOrganizations extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-019';
    }

    public function title(): string
    {
        return 'Users in multiple organizations';
    }

    public function description(): string
    {
        return 'Return users who belong to more than one organization. '
            .'Eager load their organizations and order users by email.';
    }

    public function concepts(): array
    {
        return [
            'has with count operator',
            'belongsToMany relation',
            'with',
            'orderBy',
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
        throw new LogicException('QRY-019 has not been solved yet.');
    }
}
