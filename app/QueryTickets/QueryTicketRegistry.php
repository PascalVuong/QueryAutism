<?php

namespace App\QueryTickets;

use App\QueryTickets\PhaseOne\Qry001ActiveUsers;
use Illuminate\Support\Collection;

class QueryTicketRegistry
{
    /**
     * @var array<int, class-string<QueryTicket>>
     */
    private const TICKETS = [
        Qry001ActiveUsers::class,
    ];

    /**
     * @return Collection<int, QueryTicket>
     */
    public function all(): Collection
    {
        return collect(self::TICKETS)
            ->map(fn (string $ticketClass) => app($ticketClass));
    }

    public function find(string $ticketId): ?QueryTicket
    {
        return $this->all()->first(
            fn (QueryTicket $ticket) => $ticket->id() === strtoupper($ticketId),
        );
    }
}
