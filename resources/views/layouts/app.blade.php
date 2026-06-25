<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        @yield('title', config('app.name'))
    </title>

    <style>
        :root {
            color-scheme: dark;
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
            background: #0f172a;
            color: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(circle at top, #1e293b, #0f172a 45%);
        }

        a {
            color: inherit;
        }

        code {
            font-family:
                "SFMono-Regular",
                Consolas,
                "Liberation Mono",
                monospace;
        }

        .site-header {
            border-bottom: 1px solid #334155;
            background: rgb(15 23 42 / 88%);
            backdrop-filter: blur(0.75rem);
        }

        .site-header__inner,
        .page {
            width: min(92%, 72rem);
            margin-inline: auto;
        }

        .site-header__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 4.5rem;
        }

        .brand {
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .brand span {
            color: #38bdf8;
        }

        .nav-link {
            border: 1px solid #475569;
            border-radius: 0.6rem;
            padding: 0.55em 0.9em;
            text-decoration: none;
            font-weight: 700;
        }

        .nav-link:hover {
            border-color: #38bdf8;
            color: #7dd3fc;
        }

        .page {
            padding-block: 3rem 5rem;
        }

        .eyebrow {
            margin: 0 0 0.5rem;
            color: #7dd3fc;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        h1,
        h2,
        p {
            margin-top: 0;
        }

        h1 {
            margin-bottom: 0.75rem;
            font-size: clamp(2rem, 5vw, 3.5rem);
            line-height: 1;
            letter-spacing: -0.05em;
        }

        h2 {
            font-size: 1.2rem;
        }

        .lead {
            max-width: 46rem;
            color: #94a3b8;
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .ticket-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(min(100%, 19rem), 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .card,
        .panel {
            border: 1px solid #334155;
            border-radius: 1rem;
            background: rgb(30 41 59 / 82%);
            box-shadow: 0 1.25rem 3rem rgb(0 0 0 / 18%);
        }

        .card {
            display: flex;
            flex-direction: column;
            padding: 1.25rem;
            text-decoration: none;
            transition:
                transform 150ms ease,
                border-color 150ms ease;
        }

        .card:hover {
            transform: translateY(-0.2rem);
            border-color: #38bdf8;
        }

        .ticket-id {
            color: #7dd3fc;
            font-size: 0.8rem;
            font-weight: 900;
            letter-spacing: 0.09em;
        }

        .card h2 {
            margin: 0.6rem 0;
        }

        .card p {
            color: #94a3b8;
            line-height: 1.6;
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            margin-top: auto;
            padding: 0;
            list-style: none;
        }

        .tag {
            border: 1px solid #475569;
            border-radius: 999rem;
            padding: 0.35em 0.65em;
            color: #cbd5e1;
            font-size: 0.75rem;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #7dd3fc;
            text-decoration: none;
            font-weight: 700;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(16rem, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }

        .panel {
            padding: 1.3rem;
        }

        .panel p,
        .panel li {
            color: #cbd5e1;
            line-height: 1.65;
        }

        .panel ul {
            margin-bottom: 0;
            padding-left: 1.2rem;
        }

        .file-path {
            display: block;
            overflow-x: auto;
            border: 1px solid #475569;
            border-radius: 0.6rem;
            background: #0f172a;
            padding: 0.8rem;
            color: #bae6fd;
        }

        .notice {
            margin-top: 1rem;
            border: 1px solid #854d0e;
            border-radius: 0.75rem;
            background: rgb(113 63 18 / 35%);
            padding: 1rem;
            color: #fde68a;
        }

        .result-table-wrapper {
            overflow-x: auto;
            margin-top: 1rem;
        }

        .result-table {
            width: 100%;
            border-collapse: collapse;
        }

        .result-table th,
        .result-table td {
            border-bottom: 1px solid #334155;
            padding: 0.8rem;
            text-align: left;
            vertical-align: top;
        }

        .result-table th {
            color: #7dd3fc;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .empty-state {
            margin-top: 1rem;
            color: #94a3b8;
        }

        @media (max-width: 48rem) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="site-header__inner">
        <a class="brand" href="{{ route('queries.index') }}">
            Query<span>Autism</span>
        </a>

        <a class="nav-link" href="{{ route('queries.index') }}">
            Query tickets
        </a>
    </div>
</header>

<main class="page">
    @yield('content')
</main>
</body>
</html>
