<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Administration</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-lg bg-white p-5 shadow border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-500">Articles</p>
                    <p class="text-2xl font-bold">{{ $postsCount }}</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow border-l-4 border-amber-500">
                    <p class="text-sm text-gray-500">Commentaires à modérer</p>
                    <p class="text-2xl font-bold">{{ $pendingCommentsCount }}</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow border-l-4 border-rose-500">
                    <p class="text-sm text-gray-500">Signalements</p>
                    <p class="text-2xl font-bold">{{ $pendingReportsCount }}</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow border-l-4 border-cyan-500">
                    <p class="text-sm text-gray-500">Vues totales</p>
                    <p class="text-2xl font-bold">{{ $totalViews }}</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-lg bg-white p-5 shadow">
                    <p class="text-sm text-gray-500">Temps moyen (s)</p>
                    <p class="text-xl font-bold">{{ $avgDuration }}</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow">
                    <p class="text-sm text-gray-500">Taux de rebond estimé</p>
                    <p class="text-xl font-bold">{{ $bounceRate }}%</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow">
                    <p class="text-sm text-gray-500">Avis en attente</p>
                    <p class="text-xl font-bold">{{ $pendingReviewsCount }}</p>
                </div>
            </div>

            @if ($countries->isNotEmpty())
                <div class="rounded-lg bg-white p-5 shadow">
                    <h3 class="font-semibold mb-2">Top pays (codes)</h3>
                    @foreach ($countries as $row)
                        <p class="text-sm text-gray-600">{{ strtoupper($row->country_code) }} — {{ $row->total }} vues</p>
                    @endforeach
                </div>
            @endif

            <div class="rounded-lg bg-white p-5 shadow space-y-3">
                <h3 class="text-lg font-semibold">Articles populaires</h3>
                @forelse ($topPosts as $post)
                    <div class="flex justify-between border-b pb-2">
                        <a class="text-indigo-600" href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a>
                        <span class="text-sm text-gray-600">{{ $post->views_count }} vues</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">Aucune donnée.</p>
                @endforelse
            </div>

            <div class="rounded-lg bg-white p-5 shadow">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="grid gap-3 md:grid-cols-3 lg:grid-cols-4">
                    <a class="rounded border border-indigo-200 px-4 py-3 text-indigo-700 hover:bg-indigo-50" href="{{ route('admin.posts.index') }}">Articles</a>
                    <a class="rounded border border-indigo-200 px-4 py-3 text-indigo-700 hover:bg-indigo-50" href="{{ route('admin.posts.create') }}">Nouveau post</a>
                    <a class="rounded border border-indigo-200 px-4 py-3 text-indigo-700 hover:bg-indigo-50" href="{{ route('admin.series.index') }}">Séries</a>
                    <a class="rounded border border-indigo-200 px-4 py-3 text-indigo-700 hover:bg-indigo-50" href="{{ route('admin.media.index') }}">Médias</a>
                    <a class="rounded border border-indigo-200 px-4 py-3 text-indigo-700 hover:bg-indigo-50" href="{{ route('admin.categories.index') }}">Catégories</a>
                    <a class="rounded border border-indigo-200 px-4 py-3 text-indigo-700 hover:bg-indigo-50" href="{{ route('admin.tags.index') }}">Tags</a>
                    <a class="rounded border border-indigo-200 px-4 py-3 text-indigo-700 hover:bg-indigo-50" href="{{ route('admin.comments.index') }}">Commentaires</a>
                    <a class="rounded border border-indigo-200 px-4 py-3 text-indigo-700 hover:bg-indigo-50" href="{{ route('admin.reports.index') }}">Signalements</a>
                    <a class="rounded border border-indigo-200 px-4 py-3 text-indigo-700 hover:bg-indigo-50" href="{{ route('admin.users.index') }}">Utilisateurs</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
