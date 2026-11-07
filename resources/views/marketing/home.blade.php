@extends('layouts.marketing')

@section('content')
<section class="relative overflow-hidden">
    <div class="mx-auto max-w-6xl px-4 pt-16 pb-20 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <div class="brand text-5xl md:text-6xl font-extrabold text-[var(--mint)]">SCOM</div>
            <h1 class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight max-w-xl">{{ $tagline }}</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-lg">Capture Messenger, Instagram, and WhatsApp orders, confirm with AI, then pack, ship, collect COD, and account — modular per business.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('live-demo') }}" class="btn bg-[var(--mint)] text-white">Open Live Demo</a>
                <a href="{{ route('modules') }}" class="btn bg-[var(--sand)] text-[var(--ink)]">Build your modules</a>
            </div>
        </div>
        <div class="relative min-h-[360px] rounded-3xl bg-[#12352c] text-emerald-50 p-6 shadow-2xl" x-data="{ step: 0 }" x-init="setInterval(() => step = (step+1)%3, 2200)">
            <div class="text-xs uppercase tracking-[0.2em] text-emerald-300/80">Chat → Order</div>
            <div class="mt-6 space-y-3" x-show="step===0">
                <div class="rounded-2xl bg-white/10 p-4">আমি রহিম, 01712345678 — Blue Shirt 2 pcs size L, ঢাকা</div>
                <div class="text-emerald-200/80 text-sm">WhatsApp inbound (mock)</div>
            </div>
            <div class="mt-6 space-y-3" x-show="step===1">
                <div class="rounded-2xl bg-emerald-400/20 p-4">AI extracted · confidence 0.88<br>Customer, phone, product, address verified</div>
            </div>
            <div class="mt-6 space-y-3" x-show="step===2">
                <div class="rounded-2xl bg-[var(--coral)]/30 p-4">ORD-00042 confirmed · stock reserved · Steadfast mock label ready</div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-12">
    <h2 class="text-2xl font-extrabold">Module groups</h2>
    <p class="text-slate-600 mt-2">Enable only what your shop needs. Core modules stay on.</p>
    <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($modules->take(9) as $module)
            <div class="rounded-2xl bg-white/80 p-5 border border-black/5">
                <div class="font-bold">{{ $module['name'] }}</div>
                <div class="text-xs text-slate-500 mt-1">Group {{ $module['group'] }}</div>
            </div>
        @endforeach
    </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-12">
    <h2 class="text-2xl font-extrabold">Pricing teaser</h2>
    <div class="mt-6 grid md:grid-cols-3 gap-4">
        @foreach($plans as $plan)
            <div class="rounded-2xl bg-white p-6 border border-black/5">
                <div class="font-extrabold text-xl">{{ $plan->name }}</div>
                <div class="mt-2 text-3xl font-black">৳{{ number_format($plan->price_monthly/100, 0) }}<span class="text-sm font-semibold text-slate-500">/mo</span></div>
            </div>
        @endforeach
    </div>
</section>
@endsection
