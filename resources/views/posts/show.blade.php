@extends('layouts.blog')

@php
    $seoDescription = $post->meta_description ?: ($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 160));
@endphp

@section('meta_title', $post->meta_title ?: $post->title.' — Built in Benin')
@section('meta_description', $seoDescription)
@section('canonical_url', route('posts.show', $post))
@section('og_type', 'article')
@section('og_image', \App\Support\Seo::postOgImage($post))
@section('article_published_time', $post->published_at?->toIso8601String())
@section('article_modified_time', $post->updated_at->toIso8601String())
@section('article_author', $post->author->name)
@section('article_section', $post->category->name)

@push('structured_data')
    <x-json-ld :data="\App\Support\Seo::articleJsonLd($post, $seoDescription)" />
@endpush

@section('content')
    <div id="reading-progress" class="fixed top-0 left-0 z-[60] h-1 w-0 bg-brand-green transition-all"></div>

    <div class="max-w-6xl mx-auto p-6 grid gap-6 lg:grid-cols-[240px_1fr]">
        <aside class="hidden lg:block">
            <div class="sticky top-24 space-y-4">
                @if (count($tableOfContents) > 0)
                    <nav class="glass-card p-4 text-sm">
                        <p class="font-semibold text-slate-900 dark:text-slate-100 mb-2">Sommaire</p>
                        <ul class="space-y-1">
                            @foreach ($tableOfContents as $heading)
                                <li class="{{ $heading['level'] === 3 ? 'ps-3' : '' }}">
                                    <a href="#{{ $heading['id'] }}" class="text-slate-600 hover:text-brand-red dark:text-slate-400">{{ $heading['text'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @endif
                <x-newsletter-form source="sidebar" :compact="true" />
            </div>
        </aside>

        <div class="space-y-6 min-w-0">
            @if (session('status'))
                <div class="glass-card border-brand-green/40 p-3 text-brand-green dark:text-brand-green">{{ session('status') }}</div>
            @endif

            @if ($seriesPosts->isNotEmpty())
                <nav class="glass-card p-4">
                    <p class="text-xs uppercase tracking-wider text-brand-red">Série : {{ $post->series?->title }}</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($seriesPosts as $seriesPost)
                            <a href="{{ route('posts.show', $seriesPost) }}"
                               class="rounded-lg px-3 py-1 text-sm {{ $seriesPost->id === $post->id ? 'bg-brand-green text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                                Partie {{ $seriesPost->series_part }}
                            </a>
                        @endforeach
                    </div>
                </nav>
            @endif

            <article id="article-content" class="glass-card p-6 space-y-4"
                @auth data-read-url="{{ route('posts.read-progress.update', $post) }}" @endauth>
                @if ($post->image_path)
                    <img src="{{ url(\Illuminate\Support\Facades\Storage::url($post->image_path)) }}" alt="{{ $post->title }}" class="w-full rounded-2xl object-cover max-h-[460px]" />
                @endif
                @if ($youtubeId)
                    <div class="aspect-video w-full overflow-hidden rounded-2xl">
                        <iframe class="h-full w-full" src="https://www.youtube.com/embed/{{ $youtubeId }}" title="{{ $post->title }}" allowfullscreen></iframe>
                    </div>
                @endif
                @if ($post->video_path)
                    <video controls class="w-full rounded-2xl max-h-[460px] bg-black">
                        <source src="{{ url(\Illuminate\Support\Facades\Storage::url($post->video_path)) }}">
                    </video>
                @endif
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ optional($post->published_at)->format('d/m/Y') }} · {{ $post->category->name }} · {{ $post->readingTimeMinutes() }} min · {{ $viewsCount }} vues
                    @if ($post->is_featured) · <span class="text-brand-yellow">À la une</span> @endif
                </p>
                <h1 class="text-4xl font-black text-slate-900 dark:text-slate-100">{{ $post->title }}</h1>
                <p class="text-sm text-slate-600 dark:text-slate-400">Par {{ $post->author->name }}</p>

                <div class="article-body prose prose-slate max-w-none dark:prose-invert">{!! $contentHtml !!}</div>

                @include('posts.partials.cta', ['post' => $post])

                @include('posts.partials.share', ['post' => $post])

                <p class="text-sm text-slate-600 dark:text-slate-400 flex items-center gap-2">
                    <span class="text-brand-yellow">
                        @for ($i = 1; $i <= 5; $i++)
                            {{ $i <= round($averageRating) ? '★' : '☆' }}
                        @endfor
                    </span>
                    <span>Note moyenne: {{ number_format($averageRating, 1) }}/5</span>
                </p>
            </article>

            @auth
                <section class="glass-card p-5 flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Réactions :</span>
                    @php($emoji = ['fire' => '🔥', 'idea' => '💡', 'clap' => '👏', 'heart' => '❤️'])
                    @foreach ($reactionTypes as $type)
                        <form method="POST" action="{{ route('posts.reactions.store', $post) }}">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button type="submit" class="rounded-lg px-3 py-1 text-sm {{ $userReaction === $type ? 'bg-brand-green text-white' : 'bg-slate-100 dark:bg-slate-800' }}">
                                {{ $emoji[$type] }} {{ $reactionCounts[$type] ?? 0 }}
                            </button>
                        </form>
                    @endforeach
                    @if ($isBookmarked)
                        <form method="POST" action="{{ route('posts.bookmark.destroy', $post) }}">
                            @csrf @method('DELETE')
                            <button class="btn-neon text-xs">★ Retirer des favoris</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('posts.bookmark.store', $post) }}">
                            @csrf
                            <button class="rounded-lg border border-brand-green/40 px-3 py-1 text-sm text-brand-red">☆ Sauvegarder</button>
                        </form>
                    @endif
                </section>

                @if ($errors->any())
                    <div class="glass-card border-brand-red/40 p-3 text-sm text-brand-red">Merci de corriger les champs invalides.</div>
                @endif

                <div class="grid gap-6 md:grid-cols-2">
                    <section class="glass-card p-5">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Ta note</h2>
                        <form method="POST" action="{{ route('posts.ratings.store', $post) }}" class="mt-3 space-y-3">
                            @csrf
                            <div class="flex items-center gap-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="value" value="{{ $i }}" class="sr-only" @checked((int) old('value', $userRating) === $i)>
                                        <span class="text-2xl {{ $i <= (int) old('value', $userRating) ? 'text-brand-yellow' : 'text-gray-300' }}">★</span>
                                    </label>
                                @endfor
                            </div>
                            <button class="btn-neon">Enregistrer la note</button>
                        </form>
                    </section>
                    <section class="glass-card p-5">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Laisser un avis</h2>
                        <form method="POST" action="{{ route('posts.reviews.store', $post) }}" class="mt-3 space-y-3">
                            @csrf
                            <textarea name="review_content" rows="4" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-800">{{ old('review_content') }}</textarea>
                            <button class="btn-neon">Envoyer l'avis</button>
                        </form>
                    </section>
                </div>

                <section class="glass-card p-5">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Commenter</h2>
                    <form method="POST" action="{{ route('posts.comments.store', $post) }}" class="mt-3 space-y-3">
                        @csrf
                        <textarea name="comment_content" rows="3" placeholder="Ton commentaire… Utilise @pseudo pour mentionner" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-800">{{ old('comment_content') }}</textarea>
                        <input type="hidden" name="parent_id" value="{{ old('parent_id') }}" id="comment-parent-id">
                        <button class="btn-neon">Envoyer</button>
                    </form>
                </section>
            @else
                <div class="glass-card border-brand-green/30 p-3 text-brand-green dark:border-brand-green/50 dark:text-brand-green">
                    Connectez-vous pour interagir (commentaires, réactions, notes, avis, favoris).
                    <a class="underline font-semibold" href="{{ route('login') }}">Se connecter</a>
                </div>
            @endauth

            <section class="glass-card p-5">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Commentaires</h2>
                <div class="mt-4 space-y-4">
                    @forelse ($post->comments as $comment)
                        @include('posts.partials.comment', ['comment' => $comment, 'post' => $post, 'depth' => 0])
                    @empty
                        <p class="text-sm text-slate-600 dark:text-slate-400">Aucun commentaire pour le moment.</p>
                    @endforelse
                </div>
            </section>

            <div class="lg:hidden">
                <x-newsletter-form source="article" />
            </div>

            @if ($similarPosts->isNotEmpty())
                <section class="glass-card p-5">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Articles similaires</h2>
                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        @foreach ($similarPosts as $similar)
                            <a href="{{ route('posts.show', $similar) }}" class="rounded-xl border border-slate-200 p-3 hover:border-brand-green/40 dark:border-slate-700">
                                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $similar->title }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $similar->views_count }} vues</p>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <x-newsletter-form source="article" class="hidden lg:block" />
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/article.js'])
@endpush
