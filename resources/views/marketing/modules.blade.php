@extends('layouts.marketing')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-14" x-data="{ selected: [] }">
    <h1 class="text-4xl font-extrabold">Build your own plan</h1>
    <p class="mt-3 text-slate-600">Toggle modules to estimate scope. Final pricing is confirmed in Platform billing.</p>
    <div class="mt-6 flex flex-wrap gap-2">
        @foreach($presets as $key => $list)
            <button type="button" class="rounded-lg bg-[var(--sand)] px-3 py-2 text-sm font-semibold" @click="selected = {{ json_encode(array_values($list)) }}">{{ ucfirst($key) }} preset</button>
        @endforeach
    </div>
    <div class="mt-8 grid md:grid-cols-3 gap-3">
        @foreach($modules as $module)
            <label class="rounded-xl bg-white p-4 border border-black/5 flex gap-3 items-start cursor-pointer">
                <input type="checkbox" class="mt-1" value="{{ $module['key'] }}" x-model="selected" @disabled(!empty($module['core']))>
                <span>
                    <span class="font-semibold block">{{ $module['name'] }}</span>
                    <span class="text-xs text-slate-500">{{ $module['key'] }}</span>
                </span>
            </label>
        @endforeach
    </div>
    <div class="mt-8 rounded-2xl bg-[#12352c] text-emerald-50 p-6">
        <div class="text-sm uppercase tracking-wider text-emerald-300">Live estimate</div>
        <div class="text-3xl font-black mt-2" x-text="`Modules selected: ${selected.length}`"></div>
        <div class="text-emerald-100/80 mt-2">Estimate ৳{{ number_format(999,0) }} + ৳{{ number_format(199,0) }} × optional modules / month (illustrative).</div>
    </div>
</section>
@endsection
