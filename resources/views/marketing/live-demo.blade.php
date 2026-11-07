@extends('layouts.marketing')
@section('content')
<section class="mx-auto max-w-5xl px-4 py-14">
    <h1 class="text-4xl font-extrabold">Live Demo</h1>
    <p class="mt-3 text-slate-600">Password for every demo account: <strong>password</strong>. Outbound Meta/payment APIs stay mocked.</p>
    <div class="mt-8 grid md:grid-cols-2 gap-4">
        @foreach($accounts as $account)
            <form method="post" action="{{ route('live-demo.login') }}" class="rounded-2xl bg-white border border-black/5 p-5 flex items-center justify-between gap-3">
                @csrf
                <div>
                    <div class="font-bold">{{ $account['label'] }}</div>
                    <div class="text-sm text-slate-500">{{ $account['email'] }}</div>
                </div>
                <input type="hidden" name="email" value="{{ $account['email'] }}">
                <input type="hidden" name="panel" value="{{ $account['panel'] }}">
                <button class="btn bg-[var(--mint)] text-white text-sm">Login as</button>
            </form>
        @endforeach
    </div>
</section>
@endsection
