<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry030HighRiskCustomers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-030';
    }

    public function title(): string
    {
        return 'High-risk customers';
    }

    public function description(): string
    {
        return 'Return customers whose profile has a risk_score of at least '
            .'70. Order them by customer number.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'related column condition',
            'select',
            'orderBy',
            'get',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'organization_id',
            'customer_number',
            'first_name',
            'last_name',
            'status',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-030 has not been solved yet.');
    }
}
