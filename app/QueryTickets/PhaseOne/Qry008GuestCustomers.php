<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry008GuestCustomers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-008';
    }

    public function title(): string
    {
        return 'Guest customers';
    }

    public function description(): string
    {
        return 'Return customers created from the guest source. Order them by customer number.';
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
            'organization_id',
            'customer_number',
            'first_name',
            'last_name',
            'source',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-008 has not been solved yet.');
    }
}