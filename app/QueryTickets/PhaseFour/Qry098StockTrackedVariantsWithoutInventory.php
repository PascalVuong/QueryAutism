<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry098StockTrackedVariantsWithoutInventory extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-098';
    }

    public function title(): string
    {
        return 'Stock-tracked variants without inventory';
    }

    public function description(): string
    {
        return 'Return variants belonging to stock-tracked products that do '
            .'not have any inventory levels. Eager load the product and order '
            .'by SKU.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'doesntHave',
            'nested relationship filtering',
            'with',
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
            'status',
            'product.code',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-098 has not been solved yet.');
    }
}
