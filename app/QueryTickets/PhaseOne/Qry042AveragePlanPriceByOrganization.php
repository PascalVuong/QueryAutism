<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry042AveragePlanPriceByOrganization extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-042';
    }

    public function title(): string
    {
        return 'Average plan price per organization';
    }

    public function description(): string
    {
        return 'Return every organization with the average price of its '
            .'membership plans. Organizations without plans must remain in '
            .'the result. Order by organization name.';
    }

    public function concepts(): array
    {
        return [
            'withAvg',
            'aggregate relationship',
            'select',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'name',
            'membership_plans_avg_price',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-042 has not been solved yet.');
    }
}