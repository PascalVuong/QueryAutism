<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry118InventoryMovementSummaryByType extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-118';
    }

    public function title(): string
    {
        return 'Inventory movement summary by type';
    }

    public function description(): string
    {
        return 'Group inventory movements by type. Return the movement count '
            .'and net quantity for each type, ordered alphabetically by type.';
    }

    public function concepts(): array
    {
        return [
            'GROUP BY',
            'COUNT',
            'SUM',
            'signed quantities',
            'aggregate report',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'type',
            'movement_count',
            'net_quantity',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-118 has not been solved yet.');
    }
}
