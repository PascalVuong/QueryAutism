<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry096ActiveProductsWithCategory extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-096';
    }

    public function title(): string
    {
        return 'Active products with category';
    }

    public function description(): string
    {
        return 'Return active products with their category eager loaded. '
            .'Select only the requested product columns and order by code.';
    }

    public function concepts(): array
    {
        return [
            'where',
            'with',
            'eager loading',
            'select',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'product_category_id',
            'code',
            'name',
            'status',
            'product_type',
            'category.name',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-096 has not been solved yet.');
    }
}
