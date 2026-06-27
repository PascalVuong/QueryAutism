<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry107InventoryRunningBalances extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-107';
    }

    public function title(): string
    {
        return 'Inventory running balances';
    }

    public function description(): string
    {
        return 'Return every inventory movement with variant SKU, location '
            .'code and a calculated running quantity per variant and location. '
            .'Order by SKU, location code, occurred_at and id.';
    }

    public function concepts(): array
    {
        return [
            'window function',
            'SUM OVER',
            'PARTITION BY',
            'ORDER BY',
            'multiple joins',
            'running total',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'sku',
            'location_code',
            'type',
            'quantity',
            'quantity_after',
            'running_quantity',
            'occurred_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-107 has not been solved yet.');
    }
}
