<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry015CustomersWithProfiles extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-015';
    }

    public function title(): string
    {
        return 'Customers with profiles';
    }

    public function description(): string
    {
        return 'Return customers who have a customer profile. Eager load '
            .'the profile and order the customers by customer number.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'with',
            'hasOne relation',
            'orderBy',
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
            'email',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-015 has not been solved yet.');
    }
}
