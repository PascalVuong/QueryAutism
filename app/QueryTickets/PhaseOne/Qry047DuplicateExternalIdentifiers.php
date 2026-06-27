<?php

namespace App\QueryTickets\PhaseOne;

use App\QueryTickets\QueryTicket;
use Illuminate\Support\Collection;
use LogicException;

class Qry047DuplicateExternalIdentifiers extends QueryTicket
{
    public function id(): string
    {
        return 'QRY-047';
    }

    public function title(): string
    {
        return 'Duplicate external identifiers';
    }

    public function description(): string
    {
        return 'Find provider, identifier_type and normalized_value '
            .'combinations that occur more than once. Return their duplicate '
            .'count and order by provider, identifier_type and normalized_value.';
    }

    public function concepts(): array
    {
        return [
            'groupBy',
            'count',
            'having',
            'multiple orderBy calls',
        ];
    }

    public function expectedColumns(): array
    {
        return [
            'provider',
            'identifier_type',
            'normalized_value',
            'duplicate_count',
        ];
    }

    public function run(): Collection
    {
        throw new LogicException('QRY-047 has not been solved yet.');
    }
}