@extends('layouts.marketing')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-14">
    <h1 class="text-4xl font-extrabold">Integrations</h1>
    <div class="mt-8 grid md:grid-cols-2 gap-4">
        @foreach([
            'Channels' => 'Facebook, Instagram, WhatsApp (mock adapters + webhook contracts)',
            'Couriers' => 'Steadfast, Pathao mocks; RedX/Paperfly stubs via same interface',
            'Payments' => 'COD + manual live; bKash/SSLCommerz/Stripe/PayPal stubs',
            'AI' => 'laravel-ai-hub providers; mock extraction without API keys',
            'Catalog import' => 'laravel-ai-catalog studio for URL/photo listing drafts',
            'Attendance' => 'laravel-attendance-hub device drivers (hooked, demo manual punch)',
            'PDF' => 'laravel-unicode-pdf invoices with Bangla shaping',
            'Speed' => 'drutogoti profiling on requests',
        ] as $title => $body)
            <div class="rounded-2xl bg-white p-5 border border-black/5">
                <div class="font-bold">{{ $title }}</div>
                <p class="text-sm text-slate-600 mt-2">{{ $body }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
