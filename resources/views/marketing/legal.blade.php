@extends('layouts.marketing')
@section('content')
<section class="mx-auto max-w-3xl px-4 py-14">
    <h1 class="text-4xl font-extrabold">{{ $cms->title }}</h1>
    <div class="mt-6 prose max-w-none text-slate-700 whitespace-pre-line">{{ $cms->body }}</div>
</section>
@endsection
