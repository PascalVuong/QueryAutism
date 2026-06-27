<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry078ReservationsWithoutPayments extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-078';
    }

    public function title(): string
    {
        return 'Reservations without payments';
    }

    public function description(): string
    {
        return 'Return reservations that do not have any payment records. '
            .'Order by reference number.';
    }

    public function concepts(): array
    {
        return [
            'doesntHave',
            'NOT EXISTS',
            'relationship query',
            'select',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'reference_number',
            'status',
            'total',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-078 has not been solved yet.');
    }
}
