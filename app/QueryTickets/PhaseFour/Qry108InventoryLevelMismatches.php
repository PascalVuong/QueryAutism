<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry108InventoryLevelMismatches extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-108';
    }

    public function title(): string
    {
        return 'Inventory level mismatches';
    }

    public function description(): string
    {
        return 'Return inventory levels whose stored on-hand quantity differs '
            .'from the sum of movement quantities for the same variant and '
            .'location. Include both quantities and their difference. Order '
            .'by SKU and location code.';
    }

    public function concepts(): array
    {
        return [
            'correlated subquery',
            'SUM',
            'COALESCE',
            'whereColumn',
            'calculated column',
            'reconciliation',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'sku',
            'location_code',
            'stored_quantity',
            'calculated_quantity',
            'difference',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-108 has not been solved yet.');
    }
}
