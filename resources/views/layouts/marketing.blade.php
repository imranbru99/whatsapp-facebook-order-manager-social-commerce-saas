<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('features.product_name') }} | Social Commerce</title>
    <meta name="description" content="{{ $meta ?? config('features.tagline') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Noto+Sans+Bengali:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0f1c17;
            --foam: #f3faf6;
            --mint: #1f8a70;
            --sand: #efe7d8;
            --coral: #d95d39;
        }
        body { font-family: Manrope, "Noto Sans Bengali", sans-serif; color: var(--ink); background:
            radial-gradient(1200px 600px at 10% -10%, #d8f3e7 0%, transparent 55%),
            radial-gradient(900px 500px at 100% 0%, #f7e7d5 0%, transparent 50%),
            linear-gradient(180deg, #f7fbf8 0%, #eef6f1 100%); }
        .brand { letter-spacing: -0.04em; }
        .btn { display:inline-flex; align-items:center; justify-content:center; border-radius:0.5rem; padding:0.75rem 1.25rem; font-weight:600; transition:0.15s ease; }
    </style>
</head>
<body class="min-h-screen">
<header class="border-b border-black/5 bg-white/70 backdrop-blur sticky top-0 z-40">
    <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="brand text-xl font-extrabold text-[var(--mint)]">SCOM</a>
        <nav class="hidden md:flex items-center gap-5 text-sm font-semibold text-slate-700">
            <a href="{{ route('features') }}">Features</a>
            <a href="{{ route('modules') }}">Modules</a>
            <a href="{{ route('pricing') }}">Pricing</a>
            <a href="{{ route('how-it-works') }}">How it works</a>
            <a href="{{ route('integrations') }}">Integrations</a>
            <a href="{{ route('blog') }}">Blog</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>
        <a href="{{ route('live-demo') }}" class="rounded-lg bg-[var(--mint)] px-4 py-2 text-sm font-bold text-white">Live Demo</a>
    </div>
</header>
<main>
    @yield('content')
</main>
<footer class="mt-20 border-t border-black/5 bg-[#0f1c17] text-emerald-50">
    <div class="mx-auto max-w-6xl px-4 py-12 grid gap-8 md:grid-cols-3">
        <div>
            <div class="brand text-2xl font-extrabold text-emerald-300">SCOM</div>
            <p class="mt-3 text-sm text-emerald-100/80">{{ config('features.tagline') }}</p>
        </div>
        <div class="text-sm space-y-2">
            <a class="block hover:text-white" href="{{ route('legal', 'terms') }}">Terms</a>
            <a class="block hover:text-white" href="{{ route('legal', 'privacy') }}">Privacy</a>
            <a class="block hover:text-white" href="{{ route('legal', 'refund') }}">Refund</a>
            <a class="block hover:text-white" href="{{ route('legal', 'cookies') }}">Cookies</a>
        </div>
        <div class="text-sm text-emerald-100/80">
            Built by <a class="underline" href="https://imrandev.bd">Imran Dev BD</a><br>
            Panels: /platform · /app · /agent · /rider
        </div>
    </div>
</footer>
</body>
</html>
