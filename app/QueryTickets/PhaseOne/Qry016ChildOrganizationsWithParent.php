<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry016ChildOrganizationsWithParent extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-016';
    }

    public function title(): string
    {
        return 'Child organizations with parent';
    }

    public function description(): string
    {
        return 'Return organizations that have a parent. Eager load the '
            .'parent with only id, name, slug and status. Order the child '
            .'organizations by name.';
    }

    public function concepts(): array
    {
        return [
            'whereNotNull',
            'belongsTo relation',
            'constrained eager loading',
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
        throw new LogicException('QRY-016 has not been solved yet.');
    }
}
