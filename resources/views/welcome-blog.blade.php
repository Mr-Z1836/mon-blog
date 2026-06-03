@extends('layouts.blog')

@section('meta_title', config('app.name', "Harry's Blog").' - Accueil')
@section('meta_description', 'Bienvenue sur Harry\'s Blog. Découvre les articles publiés et les derniers contenus.')
@section('canonical_url', route('home'))

@section('content')
    <section class="mx-auto flex min-h-[calc(100vh-90px)] max-w-4xl items-center px-6 py-10">
        <div class="glass-card w-full p-10 text-center">
            <h1 class="mt-3 text-4xl font-black text-slate-900 dark:text-slate-100 md:text-5xl">{{ $greeting }}</h1>
            <p class="mt-4 text-slate-600 dark:text-slate-400">
                Partages, tutoriels et retours d'expérience autour du développement web.
            </p>
            <div class="mt-8">
                <a href="{{ route('login') }}" class="btn-neon text-base">
                    Découvrir le blog
                </a>
            </div>
        </div>
    </section>
@endsection
