<?php

namespace App\QueryTickets\PhaseFour;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry097ProductsWithoutVariants extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-097';
    }

    public function title(): string
    {
        return 'Products without variants';
    }

    public function description(): string
    {
        return 'Return products that do not have any variants. Order by code.';
    }

    public function concepts(): array
    {
        return [
            'doesntHave',
            'NOT EXISTS',
            'relationship query',
            'select',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'code',
            'name',
            'status',
            'product_type',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-097 has not been solved yet.');
    }
}
