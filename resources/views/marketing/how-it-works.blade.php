@extends('layouts.marketing')
@section('content')
<section class="mx-auto max-w-3xl px-4 py-14">
    <h1 class="text-4xl font-extrabold">How it works</h1>
    <ol class="mt-8 space-y-6">
        @foreach([
            'Connect Facebook Page / WhatsApp number (mock adapters in demo).',
            'Messages land in the Unified Inbox for agents.',
            'Create order from chat or let AI extract Bangla/English fields.',
            'Confirm, reserve stock, pack, and hand over to courier mocks.',
            'Collect COD/manual payment and auto-post accounting journals.',
            'Review dashboards, campaigns, tickets, and HR attendance hooks.',
        ] as $i => $step)
            <li class="rounded-2xl bg-white p-5 border border-black/5 flex gap-4">
                <div class="h-10 w-10 rounded-full bg-[var(--mint)] text-white flex items-center justify-center font-bold">{{ $i+1 }}</div>
                <p class="pt-2">{{ $step }}</p>
            </li>
        @endforeach
    </ol>
</section>
@endsection
