<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry099AvailableStockPerLocation extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-099';
    }

    public function title(): string
    {
        return 'Available stock per location';
    }

    public function description(): string
    {
        return 'Return every inventory level with location code, variant SKU, '
            .'on-hand quantity, reserved quantity and available quantity. '
            .'Available quantity is on hand minus reserved. Order by location '
            .'code and SKU.';
    }

    public function concepts(): array
    {
        return [
            'join',
            'calculated column',
            'selectRaw',
            'arithmetic expression',
            'multiple orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'location_code',
            'sku',
            'quantity_on_hand',
            'quantity_reserved',
            'available_quantity',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-099 has not been solved yet.');
    }
}
