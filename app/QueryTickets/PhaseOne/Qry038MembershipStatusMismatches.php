<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry038MembershipStatusMismatches extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-038';
    }

    public function title(): string
    {
        return 'Membership status mismatches';
    }

    public function description(): string
    {
        return 'Return memberships whose stored status differs from the '
            .'to_status of effectiveStatusHistory. Eager load that relation '
            .'and order by membership number.';
    }

    public function concepts(): array
    {
        return [
            'whereHas',
            'whereColumn',
            'with',
            'ofMany',
            'orderBy',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'id',
            'membership_number',
            'status',
            'effectiveStatusHistory.to_status',
            'effectiveStatusHistory.effective_at',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-038 has not been solved yet.');
    }
}
