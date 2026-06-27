<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry120OrganizationCommerceReport extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-120';
    }

    public function title(): string
    {
        return 'Organization commerce report';
    }

    public function description(): string
    {
        return 'Return every organization with category count, product count, '
            .'variant count, stock location count, sales order count, '
            .'fulfilled-or-paid order count, stored completed-order revenue '
            .'and available stock. Include organizations without commerce '
            .'activity and order by organization name.';
    }

    public function concepts(): array
    {
        return [
            'aggregate subqueries',
            'multiple relationship levels',
            'conditional aggregates',
            'COALESCE',
            'multi-tenant commerce report',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'organization_name',
            'category_count',
            'product_count',
            'variant_count',
            'stock_location_count',
            'order_count',
            'completed_order_count',
            'sales_revenue',
            'available_stock',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-120 has not been solved yet.');
    }
}
