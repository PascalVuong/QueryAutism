<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry117VariantsStockedAtMultipleLocations extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-117';
    }

    public function title(): string
    {
        return 'Variants stocked at multiple locations';
    }

    public function description(): string
    {
        return 'Return product variants having inventory levels at two or '
            .'more stock locations. Include inventory_levels_count and eager '
            .'load inventory levels with their stock locations. Order by SKU.';
    }

    public function concepts(): array
    {
        return [
            'has with count',
            'withCount',
            'nested eager loading',
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
            'inventory_levels_count',
            'inventoryLevels.stockLocation.code',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-117 has not been solved yet.');
    }
}
