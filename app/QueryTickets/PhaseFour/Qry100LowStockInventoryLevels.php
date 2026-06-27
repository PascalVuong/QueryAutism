<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry100LowStockInventoryLevels extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-100';
    }

    public function title(): string
    {
        return 'Low-stock inventory levels';
    }

    public function description(): string
    {
        return 'Return inventory levels whose available quantity is less than '
            .'or equal to the reorder point. Eager load the variant, product '
            .'and stock location. Order by variant SKU.';
    }

    public function concepts(): array
    {
        return [
            'whereRaw',
            'calculated filter',
            'nested eager loading',
            'with',
            'orderBy relationship column',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'product_variant_id',
            'stock_location_id',
            'quantity_on_hand',
            'quantity_reserved',
            'reorder_point',
            'productVariant.sku',
            'productVariant.product.name',
            'stockLocation.code',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-100 has not been solved yet.');
    }
}
