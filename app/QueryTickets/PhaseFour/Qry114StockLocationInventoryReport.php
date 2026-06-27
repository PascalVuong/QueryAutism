<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry114StockLocationInventoryReport extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-114';
    }

    public function title(): string
    {
        return 'Stock location inventory report';
    }

    public function description(): string
    {
        return 'Return every stock location with variant count, on-hand '
            .'quantity, reserved quantity, available quantity and low-stock '
            .'level count. Include locations without inventory and order by '
            .'location code.';
    }

    public function concepts(): array
    {
        return [
            'leftJoin',
            'COUNT',
            'SUM',
            'CASE WHEN',
            'COALESCE',
            'inventory report',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'location_code',
            'variant_count',
            'quantity_on_hand',
            'quantity_reserved',
            'available_quantity',
            'low_stock_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-114 has not been solved yet.');
    }
}
