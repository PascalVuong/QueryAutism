<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry083NetPaidAmountPerReservation extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-083';
    }

    public function title(): string
    {
        return 'Net paid amount per reservation';
    }

    public function description(): string
    {
        return 'Return every reservation with the net collected amount from '
            .'successful payments: amount minus refunded_amount. Failed and '
            .'cancelled payments count as zero. Order by reference number.';
    }

    public function concepts(): array
    {
        return [
            'leftJoin',
            'conditional aggregate',
            'SUM',
            'CASE WHEN',
            'COALESCE',
            'groupBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'reference_number',
            'reservation_total',
            'net_paid_amount',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-083 has not been solved yet.');
    }
}
