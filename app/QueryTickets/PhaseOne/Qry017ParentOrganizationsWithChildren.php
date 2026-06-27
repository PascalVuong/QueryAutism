<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry017ParentOrganizationsWithChildren extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-017';
    }

    public function title(): string
    {
        return 'Parent organizations with children';
    }

    public function description(): string
    {
        return 'Return organizations that have child organizations. '
            .'Eager load the children, order the parents by name and '
            .'order each collection of children by name.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'hasMany relation',
            'ordered eager loading',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'parent_id',
            'name',
            'slug',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-017 has not been solved yet.');
    }
}
