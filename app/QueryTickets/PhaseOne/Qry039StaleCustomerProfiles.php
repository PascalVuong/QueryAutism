<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry039StaleCustomerProfiles extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-039';
    }

    public function title(): string
    {
        return 'Stale customer profiles';
    }

    public function description(): string
    {
        return 'Return customer profiles whose last recalculation is older '
            .'than 30 days. Exclude null timestamps, eager load the customer '
            .'and show the oldest recalculation first.';
    }

    public function concepts(): array
    {
        return [
            'whereNotNull',
            'relative date comparison',
            'with',
            'constrained eager loading',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'customer_id',
            'risk_score',
            'last_recalculated_at',
            'customer.customer_number',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-039 has not been solved yet.');
    }
}
