@extends('layouts.marketing')
@section('content')
<section class="mx-auto max-w-4xl px-4 py-14">
    <h1 class="text-4xl font-extrabold">Blog</h1>
    <div class="mt-8 space-y-4">
        @forelse($posts as $post)
            <article class="rounded-2xl bg-white p-6 border border-black/5">
                <h2 class="text-2xl font-bold">{{ $post->title }}</h2>
                <p class="mt-2 text-slate-600">{{ $post->excerpt }}</p>
            </article>
        @empty
            <p>No posts yet.</p>
        @endforelse
    </div>
</section>
@endsection
