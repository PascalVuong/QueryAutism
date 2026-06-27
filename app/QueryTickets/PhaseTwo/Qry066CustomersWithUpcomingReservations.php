<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry066CustomersWithUpcomingReservations extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-066';
    }

    public function title(): string
    {
        return 'Customers with upcoming reservations';
    }

    public function description(): string
    {
        return 'Return customers with at least one future reservation that is '
            .'not cancelled. Order by customer number.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'date filter',
            'whereNot',
            'relationship query',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'customer_number',
            'first_name',
            'last_name',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-066 has not been solved yet.');
    }
}