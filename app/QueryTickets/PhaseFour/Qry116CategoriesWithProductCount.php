<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry116CategoriesWithProductCount extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-116';
    }

    public function title(): string
    {
        return 'Categories with product count';
    }

    public function description(): string
    {
        return 'Return every product category with its number of products. '
            .'Include empty categories and order by category name.';
    }

    public function concepts(): array
    {
        return [
            'withCount',
            'relationship aggregate',
            'include zero counts',
            'select',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'status',
            'products_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-116 has not been solved yet.');
    }
}
