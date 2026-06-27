<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry106InventoryMovementTimeline extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-106';
    }

    public function title(): string
    {
        return 'Inventory movement timeline';
    }

    public function description(): string
    {
        return 'Return the inventory movement timeline for variant '
            .'GV-BALL-12 at stock location GV-PRO. Order by occurred_at and '
            .'then by id.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'multiple relationship filters',
            'history table',
            'orderBy',
            'select',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'type',
            'quantity',
            'quantity_after',
            'occurred_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-106 has not been solved yet.');
    }
}
