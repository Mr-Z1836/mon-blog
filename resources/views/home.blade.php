@extends('layouts.blog')

@php
    $hasSearch = filled($keyword);
    $isPaginated = $posts->currentPage() > 1;
    $categoryLabel = $selectedCategory
        ? $categories->firstWhere('slug', $selectedCategory)?->nom
        : null;
    $tagLabel = $selectedTag
        ? $tags->firstWhere('slug', $selectedTag)?->nom
        : null;
    $listTitle = match (true) {
        filled($categoryLabel) && filled($tagLabel) => "{$categoryLabel} · {$tagLabel} — Articles",
        filled($categoryLabel) => "{$categoryLabel} — Articles",
        filled($tagLabel) => "Tag {$tagLabel} — Articles",
        default => 'Articles',
    };
    $listDescription = match (true) {
        filled($categoryLabel) => "Articles {$categoryLabel} sur Built in Benin by Harry DEDJI : tech, entrepreneuriat et opportunités pour la jeunesse africaine.",
        filled($tagLabel) => "Articles tagués « {$tagLabel} » sur Built in Benin by Harry DEDJI.",
        default => config('blog.meta_description'),
    };
    $canonicalParams = array_filter([
        'category' => $selectedCategory ?: null,
        'tag' => $selectedTag ?: null,
    ]);
@endphp

@section('meta_title', $listTitle.' — Built in Benin by Harry DEDJI')
@section('meta_description', $listDescription)
@section('canonical_url', $hasSearch ? route('posts.index') : route('posts.index', $canonicalParams))
@if ($hasSearch || $isPaginated)
    @section('meta_robots', 'noindex, follow')
@endif

@push('head')
    @if ($posts->previousPageUrl())
        <link rel="prev" href="{{ $posts->previousPageUrl() }}">
    @endif
    @if ($posts->nextPageUrl())
        <link rel="next" href="{{ $posts->nextPageUrl() }}">
    @endif
@endpush

@section('content')
    <div class="max-w-7xl mx-auto p-6 grid gap-8 lg:grid-cols-[1fr_300px]">
    <div class="space-y-8 min-w-0">
        @if (session('status'))
            <div class="glass-card border-brand-green/40 p-3 text-brand-green">{{ session('status') }}</div>
        @endif

        <div class="glass-card overflow-hidden p-6">
            <p class="text-xs uppercase tracking-[0.3em] text-brand-green">Tech & Entrepreneuriat africain</p>
            <h1 class="mt-3 text-4xl font-black leading-tight md:text-5xl text-slate-800 dark:text-slate-100">
                Les articles <x-brand-logo size="lg" class="inline" />
            </h1>
            <p class="mt-3 max-w-2xl text-sm text-slate-600 dark:text-slate-400">
                Dev, entrepreneuriat, crypto, cybersécurité, études et opportunités — filtre par catégorie ou tag, ou cherche un mot-clé.
            </p>

            <form method="GET" action="{{ route('posts.index') }}" class="mt-4 grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                <input
                    type="text"
                    name="q"
                    value="{{ $keyword }}"
                    placeholder="Rechercher un article..."
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 placeholder:text-slate-400 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 lg:col-span-2"
                />
                <select name="category" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">Toutes les catégories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>
                            {{ $category->nom }}
                        </option>
                    @endforeach
                </select>
                <select name="tag" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">Tous les tags</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->slug }}" @selected($selectedTag === $tag->slug)>
                            {{ $tag->nom }}
                        </option>
                    @endforeach
                </select>
                <div class="flex items-center gap-2 md:col-span-2 lg:col-span-4">
                    <button type="submit" class="btn-neon">Filtrer</button>
                    <a href="{{ route('posts.index') }}" class="rounded-xl border border-brand-red px-4 py-2 text-brand-red hover:bg-brand-yellow/10">Réinitialiser</a>
                </div>
            </form>
        </div>

        @auth
            @if (auth()->user()->est_administrateur)
                <a href="{{ route('admin.dashboard') }}" class="btn-neon inline-flex text-sm">
                    Accéder à l'administration
                </a>
            @endif
        @endauth

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <article class="glass-card p-5 transition hover:-translate-y-1 hover:border-brand-green/50/40">
                    @if ($post->imageUrl())
                        <img src="{{ $post->imageUrl() }}" alt="{{ $post->titre }}" class="mb-3 h-44 w-full rounded-xl object-cover" />
                    @elseif ($post->videoUrl())
                        <video class="mb-3 h-44 w-full rounded-xl object-cover" muted controls>
                            <source src="{{ $post->videoUrl() }}">
                        </video>
                    @endif
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ optional($post->publie_le)->format('d/m/Y') }} · {{ $post->category->nom }} · {{ $post->readingTimeMinutes() }} min
                    </p>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                        @if ($post->est_epingle)<span class="text-brand-red text-xs">📌 </span>@endif
                        {{ $post->titre }}
                    </h2>
                    <p class="mt-2 text-sm text-slate-600">{{ $post->resume ?: \Illuminate\Support\Str::limit($post->contenu, 120) }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($post->tags as $tag)
                            <span class="chip">{{ $tag->nom }}</span>
                        @endforeach
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $post->views_count }} vues</span>
                        <span class="flex items-center gap-1">
                            @php($avg = (float) $post->ratings_avg_value)
                            <span class="text-brand-yellow">
                                @for ($i = 1; $i <= 5; $i++)
                                    {{ $i <= round($avg) ? '★' : '☆' }}
                                @endfor
                            </span>
                            <span>{{ number_format($avg, 1) }}/5</span>
                        </span>
                    </div>
                    <a href="{{ route('posts.show', $post) }}" class="mt-4 inline-block text-sm font-semibold text-brand-green hover:text-brand-green">Lire l'article</a>
                </article>
            @empty
                <p class="text-slate-600">Aucun article trouvé pour ce filtre.</p>
            @endforelse
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-600">
                Affichage de {{ $posts->firstItem() ?? 0 }} à {{ $posts->lastItem() ?? 0 }} sur {{ $posts->total() }} article(s)
            </p>
            {{ $posts->links() }}
        </div>

        <div class="lg:hidden">
            <x-newsletter-form source="home" />
        </div>
    </div>

    <aside class="hidden lg:block">
        <div class="sticky top-24">
            <x-newsletter-form source="sidebar" />
        </div>
    </aside>
    </div>
@endsection
