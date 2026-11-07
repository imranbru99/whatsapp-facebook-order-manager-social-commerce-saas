@extends('layouts.marketing')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-14">
    <h1 class="text-4xl font-extrabold">{{ $business->name }}</h1>
    <p class="text-slate-600 mt-2">Storefront lite · same catalog as the back office</p>
    @if(session('status'))
        <div class="mt-4 rounded-xl bg-emerald-50 text-emerald-900 px-4 py-3">{{ session('status') }}</div>
    @endif
    <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($products as $product)
            <div class="rounded-2xl bg-white border border-black/5 p-5">
                <div class="font-bold">{{ $product->name }}</div>
                <div class="text-[var(--mint)] font-black mt-2">৳{{ number_format($product->price/100, 2) }}</div>
                <form method="post" action="{{ route('storefront.checkout', $business) }}" class="mt-4 space-y-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input class="w-full rounded-lg border px-3 py-2 text-sm" name="name" placeholder="Your name" required>
                    <input class="w-full rounded-lg border px-3 py-2 text-sm" name="phone" placeholder="Phone" required>
                    <input class="w-full rounded-lg border px-3 py-2 text-sm" name="address" placeholder="Address" required>
                    <button class="btn w-full bg-[var(--coral)] text-white text-sm">Order COD</button>
                </form>
            </div>
        @endforeach
    </div>
</section>
@endsection
