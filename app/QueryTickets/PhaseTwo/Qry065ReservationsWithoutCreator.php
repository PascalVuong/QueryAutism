<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry065ReservationsWithoutCreator extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-065';
    }

    public function title(): string
    {
        return 'Reservations without a creating user';
    }

    public function description(): string
    {
        return 'Return reservations that were not created by a staff user. '
            .'Order by reference number.';
    }

    public function concepts(): array
    {
        return ['select', 'whereNull', 'orderBy', 'get'];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'reference_number',
            'status',
            'created_by_user_id',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-065 has not been solved yet.');
    }
}