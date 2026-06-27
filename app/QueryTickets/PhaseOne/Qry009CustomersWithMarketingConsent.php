<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry009CustomersWithMarketingConsent extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-009';
    }

    public function title(): string
    {
        return 'Customers with marketing consent';
    }

    public function description(): string
    {
        return 'Return customers who consented to marketing. Order them by customer number.';
    }

    public function concepts(): array
    {
        return [
            'select',
            'boolean where',
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
            'email',
            'marketing_consent',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-009 has not been solved yet.');
    }
}