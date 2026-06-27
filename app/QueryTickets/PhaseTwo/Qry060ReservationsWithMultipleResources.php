<?php

namespace App\QueryTickets\PhaseTwo;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry060ReservationsWithMultipleResources extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-060';
    }

    public function title(): string
    {
        return 'Reservations with multiple resources';
    }

    public function description(): string
    {
        return 'Return reservations containing at least two reservation items and include items_count. Order them by reference number.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'has with count operator',
            'withCount',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'reference_number',
            'items_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException(
            'QRY-060 has not been solved yet.',
        );
    }
}