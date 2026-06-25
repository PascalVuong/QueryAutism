<?php

namespace App\Http\Controllers;

use App\QueryTickets\QueryTicketRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use LogicException;

class QueryTicketController extends Controller
{
    public function index(QueryTicketRegistry $registry): View
    {
        return view('queries.index', [
            'tickets' => $registry->all(),
        ]);
    }

    public function show(
        string $ticket,
        QueryTicketRegistry $registry,
    ): View {
        $queryTicket = $registry->find($ticket);

        abort_if(is_null($queryTicket), 404);

        $results = new Collection();
        $executionMessage = null;

        try {
            $results = $queryTicket->run();
        } catch (LogicException $exception) {
            $executionMessage = $exception->getMessage();
        }

        return view('queries.show', [
            'ticket' => $queryTicket,
            'results' => $results,
            'executionMessage' => $executionMessage,
        ]);
    }
}
