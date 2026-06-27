<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry001ActiveUsers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-001';
    }

    public function title(): string
    {
        return 'Active users ordered by latest login';
    }

    public function description(): string
    {
        return 'Return all active users. Order users with the most recent '
            .'login first.';
    }

    public function concepts(): array
    {
        return [
            'Eloquent query builder',
            'where',
            'orderByDesc',
            'get',
            'NULL ordering',
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
        /*
         * TODO: Build the Eloquent query for QRY-001.
         *
         * Requirements:
         * - only users with status "active";
         * - most recent last_login_at first;
         * - users who never logged in must be placed last;
         * - return the columns listed in expectedColumns().
         */

        throw new LogicException('QRY-001 has not been solved yet.');
    }
}
