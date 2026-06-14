<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestion des commentaires</h2>
                <p class="mt-1 text-sm text-gray-500">Modère, approuve ou supprime les commentaires du blog.</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="text-sm text-brand-red underline">
                Voir les signalements →
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="grid gap-4 sm:grid-cols-3">
                <a href="{{ route('admin.comments.index', ['statut' => 'tous']) }}" class="rounded-lg bg-white p-4 shadow border-l-4 border-brand-green hover:bg-brand-yellow/5">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </a>
                <a href="{{ route('admin.comments.index', ['statut' => 'en_attente']) }}" class="rounded-lg bg-white p-4 shadow border-l-4 border-brand-yellow hover:bg-brand-yellow/5">
                    <p class="text-sm text-gray-500">En attente</p>
                    <p class="text-2xl font-bold">{{ $stats['pending'] }}</p>
                </a>
                <a href="{{ route('admin.comments.index', ['statut' => 'approuves']) }}" class="rounded-lg bg-white p-4 shadow border-l-4 border-brand-red hover:bg-brand-yellow/5">
                    <p class="text-sm text-gray-500">Approuvés</p>
                    <p class="text-2xl font-bold">{{ $stats['approved'] }}</p>
                </a>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach ([
                    'tous' => 'Tous',
                    'en_attente' => 'En attente',
                    'approuves' => 'Approuvés',
                ] as $value => $label)
                    <a
                        href="{{ route('admin.comments.index', array_filter(['statut' => $value !== 'tous' ? $value : null, 'article' => $selectedArticle])) }}"
                        class="rounded-full px-4 py-1.5 text-sm {{ $selectedStatut === $value ? 'bg-brand-green text-white' : 'bg-white text-gray-700 shadow ring-1 ring-gray-200 hover:text-brand-red' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.comments.index') }}" class="rounded-lg bg-white p-4 shadow flex flex-wrap items-end gap-3">
                @if ($selectedStatut !== 'tous')
                    <input type="hidden" name="statut" value="{{ $selectedStatut }}">
                @endif
                <div class="min-w-[16rem] flex-1">
                    <label for="article" class="block text-sm font-medium text-gray-700">Filtrer par article</label>
                    <select id="article" name="article" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        <option value="">Tous les articles</option>
                        @foreach ($articles as $article)
                            <option value="{{ $article->id }}" @selected((int) $selectedArticle === $article->id)>
                                {{ $article->titre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="rounded bg-brand-green px-4 py-2 text-sm text-white">Filtrer</button>
                @if ($selectedArticle || $selectedStatut !== 'tous')
                    <a href="{{ route('admin.comments.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm text-gray-700">Réinitialiser</a>
                @endif
            </form>

            <div class="rounded-lg bg-white p-4 shadow space-y-4">
                @forelse ($comments as $comment)
                    <div @class([
                        'border-b border-gray-100 pb-4 last:border-0',
                        'ms-4 border-s-2 border-brand-yellow/40 ps-4' => $comment->parent_id,
                    ])>
                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500">
                            <span class="font-medium text-gray-800">
                                {{ $comment->user->username ? '@'.$comment->user->username : $comment->user->name }}
                            </span>
                            <span aria-hidden="true">·</span>
                            <span>{{ $comment->created_at->locale(app()->getLocale())->diffForHumans() }}</span>
                            <span aria-hidden="true">·</span>
                            <a class="text-brand-red underline" href="{{ route('admin.posts.edit', $comment->post) }}">{{ $comment->post->titre }}</a>
                            @if ($comment->parent_id)
                                <span class="rounded-full bg-brand-yellow/20 px-2 py-0.5 text-xs font-medium text-brand-red">Réponse</span>
                            @endif
                            @if ($comment->est_approuve)
                                <span class="rounded-full bg-brand-green/10 px-2 py-0.5 text-xs font-medium text-brand-green">Approuvé</span>
                            @else
                                <span class="rounded-full bg-brand-yellow/20 px-2 py-0.5 text-xs font-medium text-brand-red">En attente</span>
                            @endif
                        </div>

                        @if ($comment->parent)
                            <div class="mt-2 rounded-md border border-brand-yellow/30 bg-brand-yellow/5 px-3 py-2 text-sm">
                                <p class="font-medium text-gray-700">
                                    Réponse à
                                    <span class="text-brand-red">
                                        {{ $comment->parent->user->username ? '@'.$comment->parent->user->username : $comment->parent->user->name }}
                                    </span>
                                </p>
                                <p class="mt-1 text-gray-500 line-clamp-2">« {{ \Illuminate\Support\Str::limit($comment->parent->contenu, 160) }} »</p>
                            </div>
                        @elseif ($comment->mentionedUser)
                            <p class="mt-2 text-sm text-gray-600">
                                Mention de
                                <span class="font-medium text-brand-red">
                                    {{ $comment->mentionedUser->username ? '@'.$comment->mentionedUser->username : $comment->mentionedUser->name }}
                                </span>
                            </p>
                        @endif

                        <p class="mt-2 text-sm text-gray-700 whitespace-pre-wrap">{{ $comment->contenu }}</p>
                        @if ($comment->pending_reports_count > 0)
                            <p class="mt-2">
                                <a
                                    href="{{ route('admin.reports.index', ['commentaire' => $comment->id]) }}"
                                    class="inline-flex items-center rounded bg-brand-red/10 px-2 py-1 text-xs font-semibold text-brand-red"
                                >
                                    {{ $comment->pending_reports_count }} signalement(s) en attente →
                                </a>
                            </p>
                        @endif
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <form method="POST" action="{{ route('admin.comments.update', $comment) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_approved" value="{{ $comment->est_approuve ? 0 : 1 }}">
                                <button class="rounded bg-brand-green px-3 py-1 text-white text-sm">
                                    {{ $comment->est_approuve ? 'Masquer' : 'Approuver' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" onsubmit="return confirm('Supprimer ce commentaire ?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded bg-red-600 px-3 py-1 text-white text-sm">Supprimer</button>
                            </form>
                            <a href="{{ route('posts.show', $comment->post) }}#comments-list" class="text-sm text-brand-red underline" target="_blank" rel="noopener">
                                Voir sur l'article
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">
                        Aucun commentaire pour ces filtres.
                    </p>
                @endforelse
            </div>

            {{ $comments->links() }}
        </div>
    </div>
</x-app-layout>
