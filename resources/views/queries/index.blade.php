@extends('layouts.app')

@section('title', 'Query tickets | QueryAutism')

@section('content')
    <p class="eyebrow">Laravel query laboratory</p>

    <h1>Query tickets</h1>

    <p class="lead">
        Solve each ticket inside its assigned PHP class. The dashboard
        executes your query and displays the returned records.
    </p>

    <section class="ticket-grid">
        @foreach ($tickets as $ticket)
            <a
                class="card"
                href="{{ route('queries.show', $ticket->id()) }}"
            >
                <span class="ticket-id">{{ $ticket->id() }}</span>

                <h2>{{ $ticket->title() }}</h2>

                <p>{{ $ticket->description() }}</p>

                <ul class="tag-list">
                    @foreach ($ticket->concepts() as $concept)
                        <li class="tag">{{ $concept }}</li>
                    @endforeach
                </ul>
            </a>
        @endforeach
    </section>
@endsection
