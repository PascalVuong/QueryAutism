<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry115InventoryAndSalesHealthReport extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-115';
    }

    public function title(): string
    {
        return 'Inventory and sales health report';
    }

    public function description(): string
    {
        return 'Return every organization with product count, stock location '
            .'count, available stock, low-stock level count, completed sales '
            .'order count, stored sales revenue and inventory mismatch count. '
            .'Include organizations without Phase Four activity and order by '
            .'organization name.';
    }

    public function concepts(): array
    {
        return [
            'aggregate subqueries',
            'correlated reconciliation',
            'conditional aggregates',
            'COALESCE',
            'multi-tenant report',
            'health report',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'organization_name',
            'product_count',
            'stock_location_count',
            'available_stock',
            'low_stock_count',
            'completed_order_count',
            'sales_revenue',
            'inventory_mismatch_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-115 has not been solved yet.');
    }
}
