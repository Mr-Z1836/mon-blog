@extends('layouts.blog')

@section('meta_title', 'Built in Benin — Tech & Entrepreneuriat africain')
@section('meta_description', config('blog.meta_description'))
@section('canonical_url', route('home'))

@push('structured_data')
    <x-json-ld :data="\App\Support\Seo::websiteJsonLd()" />
@endpush

@section('content')
    <section class="mx-auto flex min-h-[calc(100vh-90px)] max-w-4xl items-center px-6 py-10">
        <div class="glass-card w-full p-10 text-center border-brand-red/20">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brand-green">{{ $theme }}</p>
            <div class="mt-4 flex flex-col items-center gap-2">
                <x-brand-logo size="xl" />
                <p class="text-lg font-medium text-brand-yellow">{{ $tagline }}</p>
            </div>
            <p class="reading-content mx-auto mt-6 max-w-2xl">
                Code, startups, crypto, vie d'étudiant tech et opportunités pour la jeunesse africaine —
                des contenus concrets pour apprendre, entreprendre et saisir les bonnes occasions.
            </p>
            <ul class="mx-auto mt-6 flex max-w-xl flex-wrap justify-center gap-2 text-sm">
                <li><a href="{{ route('posts.index', ['category' => 'dev-code']) }}" class="chip border-brand-green/30 text-brand-green hover:bg-brand-green/10">Dev & Code</a></li>
                <li><a href="{{ route('posts.index', ['category' => 'entrepreneuriat']) }}" class="chip border-brand-yellow/40 text-brand-yellow hover:bg-brand-yellow/10">Entrepreneuriat</a></li>
                <li><a href="{{ route('posts.index', ['category' => 'crypto-finance']) }}" class="chip border-brand-red/30 text-brand-red hover:bg-brand-red/10">Crypto & Finance</a></li>
                <li><a href="{{ route('posts.index', ['category' => 'vie-etudiant']) }}" class="chip border-brand-red/30 text-brand-red hover:bg-brand-red/10">Vie d'étudiant tech</a></li>
                <li><a href="{{ route('posts.index', ['category' => 'opportunites']) }}" class="chip border-brand-yellow/30 text-brand-yellow hover:bg-brand-yellow/10">Opportunités</a></li>
            </ul>
            <div class="mt-10">
                <a href="{{ route('login') }}" class="btn-neon text-base px-8 py-3">
                    Découvrir le blog
                </a>
            </div>
        </div>
    </section>
@endsection
