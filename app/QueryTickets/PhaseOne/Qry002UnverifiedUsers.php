<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry002UnverifiedUsers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-002';
    }

    public function title(): string
    {
        return 'Unverified users';
    }

    public function description(): string
    {
        return 'Return users whose email address has not been verified. Order them by email address.';
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
            'email_verified_at',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-002 has not been solved yet.');
    }
}