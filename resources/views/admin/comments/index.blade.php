<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modération des commentaires</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <form method="GET" action="{{ route('admin.comments.index') }}" class="rounded-lg bg-white p-4 shadow flex flex-wrap items-end gap-3">
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
                @if ($selectedArticle)
                    <a href="{{ route('admin.comments.index') }}" class="rounded border border-gray-300 px-4 py-2 text-sm text-gray-700">Réinitialiser</a>
                @endif
            </form>

            <div class="rounded-lg bg-white p-4 shadow space-y-4">
                @forelse ($comments as $comment)
                    <div class="border-b pb-4">
                        <p class="text-sm text-gray-500">
                            {{ $comment->user->username ? '@'.$comment->user->username : $comment->user->name }} - <a class="text-brand-red" href="{{ route('admin.posts.edit', $comment->post) }}">{{ $comment->post->titre }}</a>
                        </p>
                        <p class="mt-2 text-sm text-gray-700">{{ $comment->contenu }}</p>
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
                        <div class="mt-3 flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.comments.update', $comment) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_approved" value="{{ $comment->est_approuve ? 0 : 1 }}">
                                <button class="rounded bg-brand-green px-3 py-1 text-white text-sm">
                                    {{ $comment->est_approuve ? 'Masquer' : 'Approuver' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded bg-red-600 px-3 py-1 text-white text-sm">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">
                        @if ($selectedArticle)
                            Aucun commentaire pour cet article.
                        @else
                            Aucun commentaire pour le moment.
                        @endif
                    </p>
                @endforelse
            </div>

            {{ $comments->links() }}
        </div>
    </div>
</x-app-layout>
