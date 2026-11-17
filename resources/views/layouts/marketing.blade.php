<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AI Voice Agent for Service Businesses') — ImranDevBD</title>
    <meta name="description" content="@yield('meta_description', 'AI voice agent for service businesses that answers calls, books jobs, qualifies leads, and syncs CRM so owners never miss revenue after hours.')">
    <meta name="author" content="Imran Ahmed">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: {{ $accent ?? '#16A34A' }};
            --ink: #0f172a;
            --muted: #475569;
            --bg: #f0fdf4;
            --card: #ffffff;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "IBM Plex Sans", system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1100px 480px at 10% -10%, rgba(22, 163, 74, 0.22), transparent 58%),
                radial-gradient(900px 420px at 100% 0%, rgba(74, 222, 128, 0.14), transparent 55%),
                linear-gradient(180deg, #f7fee7 0%, #f0fdf4 45%, #e2e8f0 100%);
            min-height: 100vh;
        }
        a { color: var(--accent); text-decoration: none; }
        .wrap { width: min(1120px, calc(100% - 2rem)); margin: 0 auto; }
        header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.25rem 0; gap: 1rem; flex-wrap: wrap;
        }
        .brand {
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.4rem; font-weight: 700; color: var(--ink);
            letter-spacing: -0.02em;
        }
        nav { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
        nav a { color: var(--muted); font-weight: 500; }
        nav a:hover { color: var(--accent); }
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--accent); color: #fff !important; border-radius: 0.75rem;
            padding: 0.7rem 1.2rem; font-weight: 700; border: 0;
        }
        .btn-outline {
            background: transparent; color: var(--accent) !important;
            border: 1.5px solid var(--accent);
        }
        .hero { padding: 3.5rem 0 2.5rem; display: grid; gap: 1.25rem; }
        .hero h1 {
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(2.1rem, 5vw, 3.4rem);
            line-height: 1.08; margin: 0; max-width: 16ch; letter-spacing: -0.03em;
        }
        .hero p { max-width: 58ch; color: var(--muted); font-size: 1.1rem; line-height: 1.6; margin: 0; }
        .cta-row { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 0.5rem; }
        .panel {
            background: var(--card);
            border: 1px solid rgba(22, 163, 74, 0.16);
            border-radius: 1.1rem;
            padding: 1.5rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }
        .grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
        .section { padding: 2rem 0 3rem; }
        .section h2 {
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            margin: 0 0 0.75rem; letter-spacing: -0.02em;
        }
        .muted { color: var(--muted); line-height: 1.6; }
        .pill {
            display: inline-block; padding: 0.2rem 0.7rem; border-radius: 999px;
            background: rgba(22, 163, 74, 0.12); color: var(--accent); font-size: 0.85rem; font-weight: 700;
        }
        .dash-shot {
            width: 100%; border-radius: 1.1rem; border: 1px solid rgba(22, 163, 74, 0.18);
            box-shadow: 0 24px 50px rgba(22, 163, 74, 0.14);
        }
        footer {
            border-top: 1px solid rgba(22, 163, 74, 0.14);
            padding: 2rem 0 3rem; color: var(--muted); font-size: 0.95rem;
        }
        code { background: rgba(22, 163, 74, 0.08); padding: 0.1rem 0.35rem; border-radius: 0.35rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 0.75rem; border-bottom: 1px solid rgba(22, 163, 74, 0.12); }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <a class="brand" href="{{ route('home') }}">VoiceServe AI</a>
            <nav>
                <a href="{{ route('features') }}">Features</a>
                <a href="{{ route('modules') }}">Modules</a>
                <a href="{{ route('pricing') }}">Pricing</a>
                <a href="{{ route('demo') }}">Demo</a>
                <a class="btn" href="/admin">Admin</a>
            </nav>
        </header>
        @yield('content')
        <footer>
            <p><strong>VoiceServe AI</strong> by <a href="https://imrandev.bd">Imran Ahmed</a> · <a href="mailto:me@imrandev.bd">me@imrandev.bd</a></p>
            <p>AI voice agent for service businesses that answers calls, books jobs, qualifies leads, and syncs CRM so owners never miss revenue after hours.</p>
        </footer>
    </div>
</body>
</html>
