@extends('layouts.marketing')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-14">
    <h1 class="text-4xl font-extrabold">Pricing</h1>
    <div class="mt-8 grid md:grid-cols-3 gap-5">
        @foreach($plans as $plan)
            <div class="rounded-3xl bg-white border border-black/5 p-8">
                <div class="text-sm font-bold uppercase tracking-wider text-[var(--mint)]">{{ $plan->code }}</div>
                <div class="text-3xl font-black mt-2">{{ $plan->name }}</div>
                <div class="mt-4 text-4xl font-black">৳{{ number_format($plan->price_monthly/100, 0) }}<span class="text-base font-semibold text-slate-500">/mo</span></div>
                <ul class="mt-6 space-y-2 text-sm text-slate-600">
                    <li>Orders/mo: {{ $plan->order_limit ?? 'Unlimited' }}</li>
                    <li>AI credits: {{ $plan->ai_credit_limit ?? 'Unlimited' }}</li>
                    <li>Staff seats: {{ $plan->staff_limit ?? 'Unlimited' }}</li>
                    <li>Channels: {{ $plan->channel_limit ?? 'Unlimited' }}</li>
                </ul>
                <a href="{{ route('live-demo') }}" class="btn mt-8 w-full bg-[var(--mint)] text-white">Try demo</a>
            </div>
        @endforeach
    </div>
</section>
@endsection
