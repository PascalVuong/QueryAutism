<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry048CrossOrganizationVerifiedIdentifiers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-048';
    }

    public function title(): string
    {
        return 'Cross-organization verified identifiers';
    }

    public function description(): string
    {
        return 'Find verified external identifiers whose provider, type and '
            .'normalized value occur in more than one organization. Return '
            .'both the organization count and identifier count.';
    }

    public function concepts(): array
    {
        return [
            'whereNotNull',
            'count distinct',
            'groupBy',
            'havingRaw',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'provider',
            'identifier_type',
            'normalized_value',
            'organization_count',
            'identifier_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-048 has not been solved yet.');
    }
}