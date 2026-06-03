@extends('layouts.blog')

@section('meta_title', 'Articles du blog - '.config('app.name', "Harry's Blog"))
@section('meta_description', 'Retrouve tous les articles du blog, filtrés par catégorie et recherchables par mot-clé.')
@section('canonical_url', route('posts.index', request()->query()))

@section('content')
    <div class="max-w-7xl mx-auto p-6 space-y-8">
        @if (session('status'))
            <div class="glass-card border-emerald-300 p-3 text-emerald-700">{{ session('status') }}</div>
        @endif

        <div class="glass-card overflow-hidden p-6">
            <p class="text-xs uppercase tracking-[0.3em] text-cyan-600/80">Nouveau contenu chaque semaine</p>
            <h1 class="mt-3 text-4xl font-black leading-tight md:text-5xl">
                Un blog développeur
                <span class="bg-gradient-to-r from-cyan-500 via-indigo-500 to-fuchsia-500 bg-clip-text text-transparent">visuel et percutant</span>
            </h1>
            <p class="mt-3 max-w-2xl text-sm text-slate-600">
                Explore les derniers articles, filtre par catégorie et retrouve rapidement les sujets qui t'intéressent.
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
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <select name="tag" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">Tous les tags</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->slug }}" @selected($selectedTag === $tag->slug)>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <div class="flex items-center gap-2 md:col-span-2 lg:col-span-4">
                    <button type="submit" class="btn-neon">Filtrer</button>
                    <a href="{{ route('posts.index') }}" class="rounded-xl border border-indigo-600 px-4 py-2 text-indigo-600 hover:bg-indigo-50">Réinitialiser</a>
                </div>
            </form>
        </div>

        @auth
            @if (auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="btn-neon inline-flex text-sm">
                    Accéder à l'administration
                </a>
            @endif
        @else
            <div class="glass-card border-cyan-200 p-3 text-sm text-cyan-700">
                <a class="underline" href="{{ route('login') }}">Connecte-toi</a> ou
                <a class="underline" href="{{ route('register') }}">crée un compte</a>
                pour commenter, noter et laisser un avis.
            </div>
        @endauth

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <article class="glass-card p-5 transition hover:-translate-y-1 hover:border-cyan-300/40">
                    @if ($post->image_path)
                        <img src="{{ url(\Illuminate\Support\Facades\Storage::url($post->image_path)) }}" alt="{{ $post->title }}" class="mb-3 h-44 w-full rounded-xl object-cover" />
                    @elseif ($post->video_path)
                        <video class="mb-3 h-44 w-full rounded-xl object-cover" muted controls>
                            <source src="{{ url(\Illuminate\Support\Facades\Storage::url($post->video_path)) }}">
                        </video>
                    @endif
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ optional($post->published_at)->format('d/m/Y') }} · {{ $post->category->name }} · {{ $post->readingTimeMinutes() }} min
                    </p>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                        @if ($post->is_pinned)<span class="text-indigo-600 text-xs">📌 </span>@endif
                        {{ $post->title }}
                    </h2>
                    <p class="mt-2 text-sm text-slate-600">{{ $post->excerpt ?: \Illuminate\Support\Str::limit($post->content, 120) }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($post->tags as $tag)
                            <span class="chip">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $post->views_count }} vues</span>
                        <span class="flex items-center gap-1">
                            @php($avg = (float) $post->ratings_avg_value)
                            <span class="text-amber-500">
                                @for ($i = 1; $i <= 5; $i++)
                                    {{ $i <= round($avg) ? '★' : '☆' }}
                                @endfor
                            </span>
                            <span>{{ number_format($avg, 1) }}/5</span>
                        </span>
                    </div>
                    <a href="{{ route('posts.show', $post) }}" class="mt-4 inline-block text-sm font-semibold text-cyan-600 hover:text-cyan-700">Lire l'article</a>
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
    </div>
@endsection
