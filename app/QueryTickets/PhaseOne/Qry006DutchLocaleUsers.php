<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry006DutchLocaleUsers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-006';
    }

    public function title(): string
    {
        return 'Users with Dutch locale';
    }

    public function description(): string
    {
        return 'Return users whose locale is nl. Order them by email address.';
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
            'locale',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-006 has not been solved yet.');
    }
}