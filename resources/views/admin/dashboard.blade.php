<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Administration</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                <div class="rounded-lg bg-white p-5 shadow border-l-4 border-brand-red">
                    <p class="text-sm text-gray-500">Articles</p>
                    <p class="text-2xl font-bold">{{ $postsCount }}</p>
                </div>
                <a href="{{ route('admin.comments.index') }}" class="rounded-lg bg-white p-5 shadow border-l-4 border-brand-yellow hover:bg-brand-yellow/5 block">
                    <p class="text-sm text-gray-500">Commentaires</p>
                    <p class="text-2xl font-bold">{{ $commentsCount }}</p>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="rounded-lg bg-white p-5 shadow border-l-4 border-brand-red hover:bg-brand-yellow/5 block">
                    <p class="text-sm text-gray-500">Signalements</p>
                    <p class="text-2xl font-bold">{{ $pendingReportsCount }}</p>
                </a>
                <a href="{{ route('admin.contact-messages.index') }}" class="rounded-lg bg-white p-5 shadow border-l-4 border-brand-green hover:bg-brand-yellow/5 block">
                    <p class="text-sm text-gray-500">Messages contact</p>
                    <p class="text-2xl font-bold">{{ $unreadContactCount }}</p>
                </a>
                <div class="rounded-lg bg-white p-5 shadow border-l-4 border-brand-yellow">
                    <p class="text-sm text-gray-500">Abonnés newsletter</p>
                    <p class="text-2xl font-bold">{{ $newsletterSubscribersCount }}</p>
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
                    <p class="text-sm text-gray-500">Vues totales</p>
                    <p class="text-xl font-bold">{{ $totalViews }}</p>
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

            <div class="rounded-lg bg-white p-5 shadow">
                <h3 class="text-lg font-semibold mb-4">Catégories</h3>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($categories as $category)
                        <div class="rounded border border-brand-green/20 px-4 py-3">
                            <p class="font-medium text-brand-red">{{ $category->nom }}</p>
                            <p class="mt-1 text-sm text-gray-600">{{ $category->posts_count }} article(s)</p>
                            <div class="mt-2 flex gap-3 text-xs">
                                <a class="text-brand-green underline" href="{{ route('posts.index', ['category' => $category->slug]) }}">Voir sur le blog</a>
                                <a class="text-brand-red underline" href="{{ route('admin.categories.edit', $category) }}">Gérer</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg bg-white p-5 shadow space-y-3">
                <h3 class="text-lg font-semibold">Articles populaires</h3>
                @forelse ($topPosts as $post)
                    <div class="flex justify-between border-b pb-2">
                        <a class="text-brand-red" href="{{ route('admin.posts.edit', $post) }}">{{ $post->titre }}</a>
                        <span class="text-sm text-gray-600">{{ $post->views_count }} vues</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">Aucune donnée.</p>
                @endforelse
            </div>

            <div class="rounded-lg bg-white p-5 shadow">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="grid gap-3 md:grid-cols-3 lg:grid-cols-4">
                    <a class="rounded border border-brand-green/30 px-4 py-3 text-brand-red hover:bg-brand-yellow/10" href="{{ route('admin.posts.index') }}">Articles</a>
                    <a class="rounded border border-brand-green/30 px-4 py-3 text-brand-red hover:bg-brand-yellow/10" href="{{ route('admin.posts.create') }}">Nouveau post</a>
                    <a class="rounded border border-brand-green/30 px-4 py-3 text-brand-red hover:bg-brand-yellow/10" href="{{ route('admin.series.index') }}">Séries</a>
                    <a class="rounded border border-brand-green/30 px-4 py-3 text-brand-red hover:bg-brand-yellow/10" href="{{ route('admin.categories.index') }}">Catégories</a>
                    <a class="rounded border border-brand-green/30 px-4 py-3 text-brand-red hover:bg-brand-yellow/10" href="{{ route('admin.tags.index') }}">Tags</a>
                    <a class="rounded border border-brand-green/30 px-4 py-3 text-brand-red hover:bg-brand-yellow/10" href="{{ route('admin.comments.index') }}">Commentaires</a>
                    <a class="rounded border border-brand-green/30 px-4 py-3 text-brand-red hover:bg-brand-yellow/10" href="{{ route('admin.reports.index') }}">Signalements</a>
                    <a class="rounded border border-brand-green/30 px-4 py-3 text-brand-red hover:bg-brand-yellow/10" href="{{ route('admin.contact-messages.index') }}">Messages contact</a>
                    <a class="rounded border border-brand-green/30 px-4 py-3 text-brand-red hover:bg-brand-yellow/10" href="{{ route('admin.newsletter-subscribers.index') }}">Newsletter</a>
                    <a class="rounded border border-brand-green/30 px-4 py-3 text-brand-red hover:bg-brand-yellow/10" href="{{ route('admin.users.index') }}">Utilisateurs</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
