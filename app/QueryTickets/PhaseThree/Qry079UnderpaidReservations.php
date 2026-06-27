<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry079UnderpaidReservations extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-079';
    }

    public function title(): string
    {
        return 'Underpaid reservations';
    }

    public function description(): string
    {
        return 'Return non-cancelled reservations with a positive total whose '
            .'successful payment amount is lower than the reservation total. '
            .'Failed payments do not count. Include paid_amount and '
            .'amount_due, ordered by reference number.';
    }

    public function concepts(): array
    {
        return [
            'leftJoin',
            'conditional aggregate',
            'HAVING',
            'CASE WHEN',
            'COALESCE',
            'calculated column',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'reference_number',
            'total',
            'paid_amount',
            'amount_due',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-079 has not been solved yet.');
    }
}
