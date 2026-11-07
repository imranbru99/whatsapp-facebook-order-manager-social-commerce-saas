@extends('layouts.marketing')
@section('content')
<section class="mx-auto max-w-xl px-4 py-14">
    <h1 class="text-4xl font-extrabold">Contact</h1>
    <form class="mt-8 space-y-4 rounded-2xl bg-white p-6 border border-black/5" method="post" action="#" onsubmit="alert('Thanks — demo form only'); return false;">
        <input class="w-full rounded-xl border border-black/10 px-4 py-3" name="name" placeholder="Name" required>
        <input class="w-full rounded-xl border border-black/10 px-4 py-3" type="email" name="email" placeholder="Email" required>
        <textarea class="w-full rounded-xl border border-black/10 px-4 py-3" name="message" rows="5" placeholder="Message" required></textarea>
        <button class="btn bg-[var(--mint)] text-white w-full">Send</button>
    </form>
</section>
@endsection
