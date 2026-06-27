<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry018UsersWithOrganizations extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-018';
    }

    public function title(): string
    {
        return 'Users with organizations';
    }

    public function description(): string
    {
        return 'Return users who belong to at least one organization. '
            .'Eager load their organizations, order users by email and '
            .'order each collection of organizations by name.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'belongsToMany relation',
            'ordered eager loading',
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
        throw new LogicException('QRY-018 has not been solved yet.');
    }
}
