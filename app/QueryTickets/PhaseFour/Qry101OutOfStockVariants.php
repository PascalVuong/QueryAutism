<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry101OutOfStockVariants extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-101';
    }

    public function title(): string
    {
        return 'Out-of-stock variants';
    }

    public function description(): string
    {
        return 'Return variants having at least one inventory level where '
            .'available quantity is zero. Eager load only those zero-available '
            .'inventory levels and their stock locations. Order by SKU.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'constrained eager loading',
            'whereRaw',
            'nested with',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'product_id',
            'sku',
            'name',
            'inventoryLevels.quantity_on_hand',
            'inventoryLevels.quantity_reserved',
            'inventoryLevels.stockLocation.code',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-101 has not been solved yet.');
    }
}
