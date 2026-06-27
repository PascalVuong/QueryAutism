<?php

namespace App\QueryTickets\PhaseThree;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry077ReservationChargeTotals extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-077';
    }

    public function title(): string
    {
        return 'Reservation charge totals';
    }

    public function description(): string
    {
        return 'Return every reservation with debit total, credit total and '
            .'net charge total. Include reservations without charges and '
            .'order by reference number.';
    }

    public function concepts(): array
    {
        return [
            'leftJoin',
            'conditional aggregate',
            'CASE WHEN',
            'SUM',
            'COALESCE',
            'groupBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'reference_number',
            'debit_total',
            'credit_total',
            'net_total',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-077 has not been solved yet.');
    }
}
