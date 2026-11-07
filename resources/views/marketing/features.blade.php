@extends('layouts.marketing')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-14">
    <h1 class="text-4xl font-extrabold">Features</h1>
    <p class="mt-3 text-slate-600">Searchable module catalog aligned with README / config/features.php.</p>
    <div class="mt-8 space-y-8" x-data="{ q: '' }">
        <input x-model="q" type="search" placeholder="Filter modules..." class="w-full max-w-md rounded-xl border border-black/10 bg-white px-4 py-3">
        @foreach($groups as $code => $label)
            <div>
                <h2 class="text-xl font-bold">Group {{ $code }} — {{ $label }}</h2>
                <div class="mt-3 grid md:grid-cols-2 gap-3">
                    @foreach($modules->where('group', $code)->merge($modules->where('group', $code === 'A' ? 'core' : $code)) as $module)
                        <div x-show="!q || '{{ strtolower($module['name'].' '.$module['key']) }}'.includes(q.toLowerCase())" class="rounded-xl bg-white p-4 border border-black/5">
                            <div class="font-semibold">{{ $module['name'] }}</div>
                            <div class="text-xs text-slate-500">{{ $module['key'] }}</div>
                            <p class="text-sm mt-2 text-slate-600">{{ $module['description'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
