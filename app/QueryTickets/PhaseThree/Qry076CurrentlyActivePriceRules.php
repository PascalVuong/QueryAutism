<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry076CurrentlyActivePriceRules extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-076';
    }

    public function title(): string
    {
        return 'Currently active price rules';
    }

    public function description(): string
    {
        return 'Return active price rules that are valid right now. A null '
            .'start or end is an open boundary. Order by priority descending '
            .'and then by code.';
    }

    public function concepts(): array
    {
        return [
            'where',
            'grouped conditions',
            'whereNull',
            'date boundaries',
            'orderByDesc',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'code',
            'name',
            'type',
            'amount',
            'percentage',
            'priority',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-076 has not been solved yet.');
    }
}
