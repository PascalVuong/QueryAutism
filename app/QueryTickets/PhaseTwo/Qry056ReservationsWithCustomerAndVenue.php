<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry056ReservationsWithCustomerAndVenue extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-056';
    }

    public function title(): string
    {
        return 'Reservations with customer and venue';
    }

    public function description(): string
    {
        return 'Return reservations with customer and venue eager loaded. Select only the requested columns and order by reference number.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'with',
            'constrained eager loading',
            'multiple relationships',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'venue_id',
            'customer_id',
            'reference_number',
            'status',
            'starts_at',
            'ends_at',
            'total',
            'customer.customer_number',
            'customer.first_name',
            'customer.last_name',
            'customer.email',
            'venue.name',
            'venue.slug',
            'venue.city',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException(
            'QRY-056 has not been solved yet.',
        );
    }
}