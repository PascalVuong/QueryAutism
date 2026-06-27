@extends('layouts.app')

@section('title', $ticket->id().' | QueryAutism')

@section('content')
    <a class="back-link" href="{{ route('queries.index') }}">
        ← Back to query tickets
    </a>

    <p class="eyebrow">{{ $ticket->id() }}</p>

    <h1>{{ $ticket->title() }}</h1>

    <p class="lead">{{ $ticket->description() }}</p>

    <div class="content-grid">
        <section class="panel">
            <h2>Query result</h2>

            @if ($executionMessage)
                <div class="notice">
                    {{ $executionMessage }}
                </div>
            @elseif ($results->isEmpty())
                <p class="empty-state">
                    The query executed successfully but returned no records.
                </p>
            @else
                <div class="result-table-wrapper">
                    <table class="result-table">
                        <thead>
                            <tr>
                                @foreach ($ticket->expectedColumns() as $column)
                                    <th>{{ $column }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($results as $result)
                                <tr>
                                    @foreach (
                                        $ticket->expectedColumns()
                                        as $column
                                    )
                                        @php
                                            $value = data_get(
                                                $result,
                                                $column,
                                            );
                                        @endphp

                                        <td>
                                            @if ($value instanceof DateTimeInterface)
                                                {{ $value->format(
                                                    'Y-m-d H:i:s',
                                                ) }}
                                            @elseif (is_bool($value))
                                                {{ $value ? 'true' : 'false' }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <aside>
            <section class="panel">
                <h2>File to edit</h2>

                <code class="file-path">{{ $ticket->filePath() }}</code>
            </section>

            <section class="panel" style="margin-top: 1rem;">
                <h2>Expected columns</h2>

                <ul>
                    @foreach ($ticket->expectedColumns() as $column)
                        <li><code>{{ $column }}</code></li>
                    @endforeach
                </ul>
            </section>

            <section class="panel" style="margin-top: 1rem;">
                <h2>Concepts</h2>

                <ul>
                    @foreach ($ticket->concepts() as $concept)
                        <li>{{ $concept }}</li>
                    @endforeach
                </ul>
            </section>
        </aside>
    </div>
@endsection
