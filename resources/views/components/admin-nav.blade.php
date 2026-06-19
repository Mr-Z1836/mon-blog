@php
    $pendingReportsCount = \App\Models\CommentReport::query()->where('statut', 'en_attente')->count();
    $unreadContactCount = \App\Models\ContactMessage::query()->whereNull('lu_le')->count();

    $linkClass = fn (bool $active) => $active
        ? 'rounded-md bg-brand-green px-3 py-1.5 font-semibold text-white'
        : 'rounded-md px-3 py-1.5 text-gray-700 hover:bg-brand-yellow/20 hover:text-brand-red';
@endphp

<nav class="border-b border-brand-green/20 bg-white" aria-label="Navigation administration">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-2 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('admin.dashboard') }}" class="{{ $linkClass(request()->routeIs('admin.dashboard')) }}">
            Tableau de bord
        </a>

        <span class="hidden text-gray-300 sm:inline" aria-hidden="true">|</span>

        <a href="{{ route('admin.comments.index') }}" class="{{ $linkClass(request()->routeIs('admin.comments.*')) }}">
            Commentaires
        </a>
        <a href="{{ route('admin.reports.index') }}" class="{{ $linkClass(request()->routeIs('admin.reports.*')) }}">
            Signalements
            @if ($pendingReportsCount > 0)
                <span class="ms-1 rounded-full bg-brand-red px-1.5 py-0.5 text-xs text-white">{{ $pendingReportsCount }}</span>
            @endif
        </a>

        <span class="hidden text-gray-300 sm:inline" aria-hidden="true">|</span>

        <a href="{{ route('admin.posts.index') }}" class="{{ $linkClass(request()->routeIs('admin.posts.*')) }}">Articles</a>
        <a href="{{ route('admin.categories.index') }}" class="{{ $linkClass(request()->routeIs('admin.categories.*')) }}">Catégories</a>
        <a href="{{ route('admin.tags.index') }}" class="{{ $linkClass(request()->routeIs('admin.tags.*')) }}">Tags</a>
        <a href="{{ route('admin.series.index') }}" class="{{ $linkClass(request()->routeIs('admin.series.*')) }}">Séries</a>

        <span class="hidden text-gray-300 sm:inline" aria-hidden="true">|</span>

        <a href="{{ route('admin.contact-messages.index') }}" class="{{ $linkClass(request()->routeIs('admin.contact-messages.*')) }}">
            Contact
            @if ($unreadContactCount > 0)
                <span class="ms-1 rounded-full bg-brand-red px-1.5 py-0.5 text-xs text-white">{{ $unreadContactCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.newsletter-subscribers.index') }}" class="{{ $linkClass(request()->routeIs('admin.newsletter-subscribers.*')) }}">Newsletter</a>
        <a href="{{ route('admin.users.index') }}" class="{{ $linkClass(request()->routeIs('admin.users.*')) }}">Utilisateurs</a>

        <a href="{{ route('posts.index') }}" class="ms-auto text-sm text-brand-red underline">
            Voir le blog
        </a>
    </div>
</nav>
